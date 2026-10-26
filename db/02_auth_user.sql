-- =====================================================================
-- Modul 02 — Auth & User
-- Use case: daftar akun, login, lupa password, sesi, preferensi kopi.
-- Figma: Halaman Login, Halaman Daftar.
-- Dependensi: modul 01.
-- =====================================================================

USE coffenfo;

CREATE TABLE IF NOT EXISTS users (
  id                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  public_id           CHAR(36) NOT NULL DEFAULT (UUID()),
  full_name           VARCHAR(120) NOT NULL,
  email               VARCHAR(255) NOT NULL,            -- simpan lowercase dari API
  username            VARCHAR(40) NULL,
  password_hash       VARCHAR(255) NOT NULL,            -- Argon2id / bcrypt, BUKAN plaintext
  role                ENUM('user','cafe_owner','admin') NOT NULL DEFAULT 'user',
  status              ENUM('active','suspended','deleted') NOT NULL DEFAULT 'active',
  avatar_url          VARCHAR(500) NULL,
  email_verified_at   DATETIME NULL,
  failed_login_count  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  locked_until        DATETIME NULL,
  last_login_at       DATETIME NULL,
  password_changed_at DATETIME NULL,
  created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at          DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_public_id (public_id),
  UNIQUE KEY uq_users_email (email),
  UNIQUE KEY uq_users_username (username),
  KEY idx_users_role_status (role, status),
  CONSTRAINT chk_users_username CHECK (username IS NULL OR CHAR_LENGTH(username) >= 3)
) ENGINE=InnoDB;

-- Preferensi saat daftar. NULL = "Semuanya"
CREATE TABLE IF NOT EXISTS user_preferences (
  user_id         BIGINT UNSIGNED NOT NULL,
  bean_type_id    TINYINT UNSIGNED NULL,
  roast_level_id  TINYINT UNSIGNED NULL,
  updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  CONSTRAINT fk_userpref_user  FOREIGN KEY (user_id)        REFERENCES users(id)        ON DELETE CASCADE,
  CONSTRAINT fk_userpref_bean  FOREIGN KEY (bean_type_id)   REFERENCES bean_types(id)   ON DELETE SET NULL,
  CONSTRAINT fk_userpref_roast FOREIGN KEY (roast_level_id) REFERENCES roast_levels(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Token sekali pakai: reset password & verifikasi email (simpan HASH, bukan token asli)
CREATE TABLE IF NOT EXISTS user_tokens (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id     BIGINT UNSIGNED NOT NULL,
  type        ENUM('password_reset','email_verify') NOT NULL,
  token_hash  CHAR(64) NOT NULL,                        -- SHA-256 hex
  expires_at  DATETIME NOT NULL,
  used_at     DATETIME NULL,
  ip_address  VARCHAR(45) NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_user_tokens_hash (token_hash),
  KEY idx_user_tokens_user_type (user_id, type),
  KEY idx_user_tokens_expires (expires_at),
  CONSTRAINT fk_user_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sesi login (refresh token dengan rotation)
CREATE TABLE IF NOT EXISTS refresh_tokens (
  id             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id        BIGINT UNSIGNED NOT NULL,
  family_id      CHAR(36) NOT NULL,                     -- satu rantai rotasi
  token_hash     CHAR(64) NOT NULL,
  user_agent     VARCHAR(255) NULL,
  ip_address     VARCHAR(45) NULL,
  expires_at     DATETIME NOT NULL,
  revoked_at     DATETIME NULL,
  replaced_by_id BIGINT UNSIGNED NULL,
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_refresh_hash (token_hash),
  KEY idx_refresh_user (user_id, revoked_at),
  KEY idx_refresh_family (family_id),
  KEY idx_refresh_expires (expires_at),
  CONSTRAINT fk_refresh_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Catatan percobaan login (identifier di-hash agar email tidak tersimpan ganda)
CREATE TABLE IF NOT EXISTS login_attempts (
  id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  identifier_hash  CHAR(64) NOT NULL,                   -- SHA-256(lowercase(email/username))
  ip_address       VARCHAR(45) NOT NULL,
  success          TINYINT(1) NOT NULL,
  user_agent       VARCHAR(255) NULL,
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_attempts_identifier (identifier_hash, created_at),
  KEY idx_attempts_ip (ip_address, created_at)
) ENGINE=InnoDB;

-- Pembersihan otomatis (butuh event_scheduler=ON)
CREATE EVENT IF NOT EXISTS ev_purge_login_attempts
  ON SCHEDULE EVERY 1 DAY
  DO DELETE FROM login_attempts WHERE created_at < UTC_TIMESTAMP() - INTERVAL 30 DAY;

DELIMITER //
CREATE EVENT IF NOT EXISTS ev_purge_expired_tokens
  ON SCHEDULE EVERY 1 DAY
  DO BEGIN
    DELETE FROM user_tokens    WHERE expires_at < UTC_TIMESTAMP() - INTERVAL 7 DAY;
    DELETE FROM refresh_tokens WHERE expires_at < UTC_TIMESTAMP() - INTERVAL 7 DAY;
  END//
DELIMITER ;

