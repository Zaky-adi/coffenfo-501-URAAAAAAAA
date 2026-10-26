-- =====================================================================
-- Modul 09 — Notifikasi, Audit Log, Pengaturan
-- Use case: ikon lonceng (admin & pemilik), jejak audit, konfigurasi aplikasi.
-- Dependensi: modul 02.
-- =====================================================================

USE coffenfo;

CREATE TABLE IF NOT EXISTS notifications (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    BIGINT UNSIGNED NOT NULL,
  type       VARCHAR(40) NOT NULL,                   -- review_approved, points_earned, order_status, new_report, ...
  title      VARCHAR(120) NOT NULL,
  body       VARCHAR(300) NULL,
  data       JSON NULL,                              -- mis. {"order_id":"uuid"}
  read_at    DATETIME NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_notif_user (user_id, read_at, created_at),
  CONSTRAINT fk_notif_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Jejak audit: APPEND-ONLY, tanpa FK agar tetap ada walau user dianonimkan
CREATE TABLE IF NOT EXISTS audit_logs (
  id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  actor_user_id BIGINT UNSIGNED NULL,
  actor_role    VARCHAR(20) NULL,
  action        VARCHAR(60) NOT NULL,                -- review.approve, review.delete, cafe.suspend, user.suspend, voucher.create, auth.login_failed, ...
  entity_type   VARCHAR(40) NOT NULL,
  entity_id     VARCHAR(40) NULL,
  ip_address    VARCHAR(45) NULL,
  user_agent    VARCHAR(255) NULL,
  metadata      JSON NULL,                           -- nilai sebelum/sesudah. JANGAN isi password/token.
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_audit_actor (actor_user_id, created_at),
  KEY idx_audit_entity (entity_type, entity_id),
  KEY idx_audit_action (action, created_at)
) ENGINE=InnoDB;

DELIMITER //
CREATE TRIGGER IF NOT EXISTS trg_audit_no_update BEFORE UPDATE ON audit_logs
FOR EACH ROW BEGIN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'audit_logs bersifat append-only';
END//
CREATE TRIGGER IF NOT EXISTS trg_audit_no_delete BEFORE DELETE ON audit_logs
FOR EACH ROW BEGIN
  SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'audit_logs bersifat append-only';
END//
DELIMITER ;

CREATE TABLE IF NOT EXISTS app_settings (
  setting_key   VARCHAR(60) NOT NULL,
  setting_value VARCHAR(255) NOT NULL,
  description   VARCHAR(200) NULL,
  updated_by    BIGINT UNSIGNED NULL,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (setting_key),
  CONSTRAINT fk_settings_user FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO app_settings (setting_key, setting_value, description) VALUES
 ('login_max_attempts',          '5',    'Gagal login berturut-turut sebelum akun dikunci'),
 ('login_lockout_minutes',       '15',   'Lama akun dikunci (menit)'),
 ('review_min_comment_length',   '30',   'Panjang minimal komentar untuk poin komentar'),
 ('review_max_length',           '500',  'Panjang maksimal komentar'),
 ('review_max_photos',           '5',    'Maksimal foto per review'),
 ('review_report_hide_threshold','3',    'Jumlah laporan berbeda yang menyembunyikan review sementara'),
 ('voucher_default_validity_days','30',  'Masa berlaku default voucher setelah ditukar'),
 ('service_fee_flat',            '2500', 'CONTOH biaya layanan (rupiah) - sesuaikan dengan Figma'),
 ('order_expire_minutes',        '30',   'Batas waktu bayar sebelum pesanan kedaluwarsa');

