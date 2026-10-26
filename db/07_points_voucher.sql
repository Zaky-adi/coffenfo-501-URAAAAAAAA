-- =====================================================================
-- Modul 07 — Poin & Voucher
-- Use case: user dapat poin dari review, menukar poin dengan voucher potongan harga yang dibuat tiap cafe.
-- Dependensi: modul 02, 03, 06.
-- =====================================================================

USE coffenfo;

CREATE TABLE IF NOT EXISTS point_rules (
  id          TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  code        VARCHAR(30) NOT NULL,
  points      SMALLINT UNSIGNED NOT NULL,
  description VARCHAR(150) NULL,
  is_active   TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_point_rules_code (code)
) ENGINE=InnoDB;

INSERT IGNORE INTO point_rules (code, points, description) VALUES
 ('review_rating', 5,  'Memberi rating bintang'),
 ('review_comment',10, 'Menulis komentar minimal 30 karakter'),
 ('review_photo',  15, 'Mengunggah minimal 1 foto');

-- Saldo (cache). Sumber kebenaran = points_ledger.
CREATE TABLE IF NOT EXISTS user_points (
  user_id         BIGINT UNSIGNED NOT NULL,
  balance         INT UNSIGNED NOT NULL DEFAULT 0,
  lifetime_earned INT UNSIGNED NOT NULL DEFAULT 0,
  updated_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  CONSTRAINT fk_user_points_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Buku besar poin: APPEND-ONLY
CREATE TABLE IF NOT EXISTS points_ledger (
  id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id         BIGINT UNSIGNED NOT NULL,
  entry_type      ENUM('earn','redeem','refund','revoke','adjust') NOT NULL,
  points          INT NOT NULL,                              -- bertanda: + masuk, - keluar
  balance_after   INT UNSIGNED NOT NULL,
  source_type     ENUM('review','voucher','order','admin') NOT NULL,
  source_id       BIGINT UNSIGNED NULL,
  idempotency_key VARCHAR(100) NOT NULL,                     -- cegah poin ganda, mis. 'review:{user_id}:{cafe_id}'
  note            VARCHAR(255) NULL,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_ledger_idem (idempotency_key),
  KEY idx_ledger_user (user_id, created_at),
  CONSTRAINT chk_ledger_points CHECK (points <> 0),
  CONSTRAINT fk_ledger_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

DELIMITER //
CREATE TRIGGER IF NOT EXISTS trg_ledger_no_update BEFORE UPDATE ON points_ledger
FOR EACH ROW BEGIN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'points_ledger bersifat append-only';
END//
CREATE TRIGGER IF NOT EXISTS trg_ledger_no_delete BEFORE DELETE ON points_ledger
FOR EACH ROW BEGIN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'points_ledger bersifat append-only';
END//
DELIMITER ;

-- Voucher dibuat oleh cafe
CREATE TABLE IF NOT EXISTS vouchers (
  id                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  public_id          CHAR(36) NOT NULL DEFAULT (UUID()),
  cafe_id            BIGINT UNSIGNED NOT NULL,
  title              VARCHAR(120) NOT NULL,
  description        VARCHAR(300) NULL,
  points_cost        INT UNSIGNED NOT NULL,
  discount_type      ENUM('percent','fixed') NOT NULL,
  discount_value     INT UNSIGNED NOT NULL,                  -- persen (1-100) atau rupiah
  max_discount       INT UNSIGNED NULL,                      -- batas potongan jika persen
  min_spend          INT UNSIGNED NOT NULL DEFAULT 0,
  stock              INT UNSIGNED NULL,                      -- NULL = tidak terbatas
  per_user_limit     TINYINT UNSIGNED NOT NULL DEFAULT 1,
  validity_days      SMALLINT UNSIGNED NOT NULL DEFAULT 30,  -- masa berlaku setelah ditukar
  valid_from         DATETIME NOT NULL,
  valid_until        DATETIME NOT NULL,
  status             ENUM('draft','active','paused','expired') NOT NULL DEFAULT 'draft',
  created_by         BIGINT UNSIGNED NULL,
  created_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_vouchers_public_id (public_id),
  KEY idx_vouchers_cafe_status (cafe_id, status, valid_until),
  CONSTRAINT chk_voucher_cost  CHECK (points_cost > 0),
  CONSTRAINT chk_voucher_value CHECK (discount_value > 0 AND (discount_type = 'fixed' OR discount_value <= 100)),
  CONSTRAINT chk_voucher_dates CHECK (valid_until > valid_from),
  CONSTRAINT fk_vouchers_cafe FOREIGN KEY (cafe_id)    REFERENCES cafes(id) ON DELETE RESTRICT,
  CONSTRAINT fk_vouchers_by   FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Voucher yang sudah ditukar user
CREATE TABLE IF NOT EXISTS voucher_redemptions (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  public_id     CHAR(36) NOT NULL DEFAULT (UUID()),
  voucher_id    BIGINT UNSIGNED NOT NULL,
  user_id       BIGINT UNSIGNED NOT NULL,
  redeem_code   CHAR(10) NOT NULL,                           -- kode acak, ditunjukkan ke kasir
  points_spent  INT UNSIGNED NOT NULL,
  status        ENUM('active','used','expired','cancelled') NOT NULL DEFAULT 'active',
  expires_at    DATETIME NOT NULL,
  used_at       DATETIME NULL,
  used_order_id BIGINT UNSIGNED NULL,                        -- FK ditambahkan di modul 08
  marked_used_by BIGINT UNSIGNED NULL,                       -- kasir/pemilik yang memvalidasi
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_redemption_public_id (public_id),
  UNIQUE KEY uq_redemption_code (redeem_code),
  KEY idx_redemption_user (user_id, status),
  KEY idx_redemption_voucher (voucher_id),
  CONSTRAINT fk_redeem_voucher FOREIGN KEY (voucher_id)     REFERENCES vouchers(id) ON DELETE RESTRICT,
  CONSTRAINT fk_redeem_user    FOREIGN KEY (user_id)        REFERENCES users(id)    ON DELETE RESTRICT,
  CONSTRAINT fk_redeem_marker  FOREIGN KEY (marked_used_by) REFERENCES users(id)    ON DELETE SET NULL
) ENGINE=InnoDB;

