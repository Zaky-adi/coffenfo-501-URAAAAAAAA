-- =====================================================================
-- Modul 13 — Perangkat, Push, Upload, Idempotensi & Konfigurasi Aplikasi (Mobile Support)
-- Use case: aplikasi mobile Flutter. Sesi per perangkat, push notification (FCM),
-- unggah foto lewat presigned URL, request idempoten saat jaringan putus, dan paksa update versi aplikasi.
-- Dependensi: modul 02 (refresh_tokens), 06 (foto review), 09 (notifications, app_settings), 12 (user_events).
-- =====================================================================

USE coffenfo;

-- 1) Sesi per perangkat
ALTER TABLE refresh_tokens
  ADD COLUMN IF NOT EXISTS client_platform ENUM('web','android','ios') NOT NULL DEFAULT 'web' AFTER family_id,
  ADD COLUMN IF NOT EXISTS device_id       VARCHAR(64)  NULL AFTER client_platform,
  ADD COLUMN IF NOT EXISTS device_name     VARCHAR(100) NULL AFTER device_id,
  ADD COLUMN IF NOT EXISTS app_version     VARCHAR(20)  NULL AFTER device_name,
  ADD COLUMN IF NOT EXISTS last_used_at    DATETIME     NULL AFTER app_version,
  ADD KEY IF NOT EXISTS idx_refresh_device (user_id, device_id);

-- 2) Perangkat + token push (FCM)
CREATE TABLE IF NOT EXISTS user_devices (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id       BIGINT UNSIGNED NOT NULL,
  device_id     VARCHAR(64) NOT NULL,                 -- UUID dibuat aplikasi saat instalasi
  platform      ENUM('android','ios') NOT NULL,
  device_name   VARCHAR(100) NULL,
  app_version   VARCHAR(20) NULL,
  locale        VARCHAR(10) NULL,
  push_token    VARCHAR(255) NULL,                    -- token FCM; sensitif sedang, jangan di-log
  push_enabled  TINYINT(1) NOT NULL DEFAULT 1,
  last_seen_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_devices_user_device (user_id, device_id),
  UNIQUE KEY uq_devices_push_token (push_token),      -- token dipindah ke user baru saat ganti akun
  KEY idx_devices_user (user_id, push_enabled),
  CONSTRAINT fk_devices_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3) Kategori & preferensi notifikasi
ALTER TABLE notifications
  ADD COLUMN IF NOT EXISTS category  ENUM('review','points','order','recommendation','promo','system') NOT NULL DEFAULT 'system' AFTER type,
  ADD COLUMN IF NOT EXISTS deep_link VARCHAR(255) NULL AFTER body;

CREATE TABLE IF NOT EXISTS notification_preferences (
  user_id       BIGINT UNSIGNED NOT NULL,
  category      ENUM('review','points','order','recommendation','promo','system') NOT NULL,
  push_enabled  TINYINT(1) NOT NULL,
  inapp_enabled TINYINT(1) NOT NULL DEFAULT 1,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, category),
  CONSTRAINT fk_notifpref_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Antrean pengiriman push (dibaca worker, ada retry dan jejak error)
CREATE TABLE IF NOT EXISTS push_outbox (
  id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  notification_id BIGINT UNSIGNED NOT NULL,
  user_device_id  BIGINT UNSIGNED NOT NULL,
  status          ENUM('pending','sent','failed','invalid_token','skipped') NOT NULL DEFAULT 'pending',
  attempts        TINYINT UNSIGNED NOT NULL DEFAULT 0,
  last_error      VARCHAR(255) NULL,
  scheduled_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,   -- bisa ditunda (jam tenang)
  sent_at         DATETIME NULL,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_outbox_notif_device (notification_id, user_device_id),
  KEY idx_outbox_pending (status, scheduled_at),
  CONSTRAINT fk_outbox_notif  FOREIGN KEY (notification_id) REFERENCES notifications(id) ON DELETE CASCADE,
  CONSTRAINT fk_outbox_device FOREIGN KEY (user_device_id)  REFERENCES user_devices(id)  ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4) Unggah foto lewat presigned URL
CREATE TABLE IF NOT EXISTS uploads (
  id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  public_id        CHAR(36) NOT NULL DEFAULT (UUID()),
  user_id          BIGINT UNSIGNED NOT NULL,
  purpose          ENUM('review_photo','cafe_photo','menu_photo','avatar') NOT NULL,
  object_key       VARCHAR(255) NOT NULL,              -- kunci acak di object storage
  content_type     VARCHAR(50) NOT NULL,
  declared_size    INT UNSIGNED NOT NULL,              -- ukuran yang diminta saat presign
  size_bytes       INT UNSIGNED NULL,                  -- ukuran sebenarnya setelah diperiksa
  width            SMALLINT UNSIGNED NULL,
  height           SMALLINT UNSIGNED NULL,
  status           ENUM('pending','processing','ready','rejected','expired') NOT NULL DEFAULT 'pending',
  rejection_reason VARCHAR(100) NULL,
  url              VARCHAR(500) NULL,                  -- URL CDN final (setelah ready)
  consumed_at      DATETIME NULL,                      -- sudah dipakai oleh review/cafe/menu/avatar
  expires_at       DATETIME NOT NULL,                  -- batas unggah (presign +10 menit)
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  confirmed_at     DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_uploads_public_id (public_id),
  UNIQUE KEY uq_uploads_object_key (object_key),
  KEY idx_uploads_user_status (user_id, status),
  KEY idx_uploads_cleanup (status, expires_at),
  CONSTRAINT chk_uploads_size CHECK (declared_size > 0 AND declared_size <= 5242880),
  CONSTRAINT fk_uploads_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5) Idempotensi request (retry aman saat jaringan seluler putus)
CREATE TABLE IF NOT EXISTS idempotency_keys (
  id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id         BIGINT UNSIGNED NOT NULL,
  idem_key        VARCHAR(80) NOT NULL,
  endpoint        VARCHAR(80) NOT NULL,                -- mis. 'POST /orders'
  request_hash    CHAR(64) NOT NULL,                   -- SHA-256 body request
  status          ENUM('processing','completed') NOT NULL DEFAULT 'processing',
  response_status SMALLINT UNSIGNED NULL,
  response_body   JSON NULL,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  expires_at      DATETIME NOT NULL,                   -- +24 jam
  PRIMARY KEY (id),
  UNIQUE KEY uq_idem (user_id, endpoint, idem_key),
  KEY idx_idem_expires (expires_at),
  CONSTRAINT fk_idem_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6) Event rekomendasi dari antrean offline Flutter: dedup + waktu klien
ALTER TABLE user_events
  ADD COLUMN IF NOT EXISTS client_event_id CHAR(36) NULL AFTER rec_request_id,
  ADD COLUMN IF NOT EXISTS client_ts       DATETIME NULL AFTER client_event_id,
  ADD UNIQUE KEY IF NOT EXISTS uq_events_client (user_id, client_event_id);

-- 7) Konfigurasi aplikasi mobile
INSERT IGNORE INTO app_settings (setting_key, setting_value, description) VALUES
 ('min_supported_version_android', '1.0.0', 'Versi minimum Android; di bawah ini API membalas 426'),
 ('min_supported_version_ios',     '1.0.0', 'Versi minimum iOS'),
 ('latest_version_android',        '1.0.0', 'Versi terbaru Android (untuk ajakan update)'),
 ('latest_version_ios',            '1.0.0', 'Versi terbaru iOS'),
 ('maintenance_mode',              '0',     '1 = API membalas 503 (kecuali /health dan /app/config)'),
 ('maintenance_message',           '',      'Pesan yang ditampilkan saat pemeliharaan'),
 ('refresh_grace_seconds',         '20',    'Jendela toleransi refresh token paralel'),
 ('push_quiet_hours',              '22:00-07:00', 'Jam tenang push (WIB), kecuali kategori order'),
 ('push_recommendation_max_per_day','1',    'Maksimal push rekomendasi per user per hari');

-- 8) Pembersihan otomatis (butuh event_scheduler=ON)
CREATE EVENT IF NOT EXISTS ev_purge_idempotency
  ON SCHEDULE EVERY 1 HOUR
  DO DELETE FROM idempotency_keys WHERE expires_at < UTC_TIMESTAMP() LIMIT 50000;

CREATE EVENT IF NOT EXISTS ev_purge_push_outbox
  ON SCHEDULE EVERY 1 DAY
  DO DELETE FROM push_outbox WHERE created_at < UTC_TIMESTAMP() - INTERVAL 14 DAY LIMIT 50000;

