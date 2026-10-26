-- =====================================================================
-- Modul 06 — Review & Rating
-- Use case: tulis ulasan (bintang + komentar + foto), lihat review, helpful, laporkan, moderasi admin.
-- Figma: Review & Rating, Modal Review, Admin – Moderasi Review & Laporan, "Kata pecinta kopi".
-- Dependensi: modul 02, 03.
-- =====================================================================

USE coffenfo;

CREATE TABLE IF NOT EXISTS reviews (
  id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  public_id        CHAR(36) NOT NULL DEFAULT (UUID()),
  cafe_id          BIGINT UNSIGNED NOT NULL,
  user_id          BIGINT UNSIGNED NOT NULL,
  rating           TINYINT UNSIGNED NOT NULL,
  comment          VARCHAR(500) NULL,                        -- Figma: batas 500 karakter
  status           ENUM('pending','approved','reported','rejected') NOT NULL DEFAULT 'pending',
  rejection_reason VARCHAR(255) NULL,
  moderated_by     BIGINT UNSIGNED NULL,
  moderated_at     DATETIME NULL,
  has_photo        TINYINT(1) NOT NULL DEFAULT 0,            -- untuk filter "Dengan Foto"
  helpful_count    INT UNSIGNED NOT NULL DEFAULT 0,          -- cache "Bermanfaat (23)"
  is_featured      TINYINT(1) NOT NULL DEFAULT 0,            -- tampil di "Kata pecinta kopi"
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_reviews_public_id (public_id),
  UNIQUE KEY uq_reviews_user_cafe (user_id, cafe_id),        -- 1 user = 1 review per cafe
  KEY idx_reviews_cafe_list (cafe_id, status, created_at),
  KEY idx_reviews_cafe_rating (cafe_id, status, rating),
  KEY idx_reviews_queue (status, created_at),
  KEY idx_reviews_featured (is_featured, status),
  CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5),
  CONSTRAINT fk_reviews_cafe FOREIGN KEY (cafe_id)      REFERENCES cafes(id) ON DELETE RESTRICT,
  CONSTRAINT fk_reviews_user FOREIGN KEY (user_id)      REFERENCES users(id) ON DELETE RESTRICT,
  CONSTRAINT fk_reviews_mod  FOREIGN KEY (moderated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS review_photos (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  review_id  BIGINT UNSIGNED NOT NULL,
  url        VARCHAR(500) NOT NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_review_photos_review (review_id, sort_order),
  CONSTRAINT fk_review_photos_review FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS review_helpful_votes (
  review_id  BIGINT UNSIGNED NOT NULL,
  user_id    BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (review_id, user_id),
  KEY idx_helpful_user (user_id),
  CONSTRAINT fk_helpful_review FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
  CONSTRAINT fk_helpful_user   FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS review_reports (
  id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  review_id        BIGINT UNSIGNED NOT NULL,
  reporter_user_id BIGINT UNSIGNED NOT NULL,
  reason           ENUM('spam','offensive','irrelevant','inappropriate','other') NOT NULL,
  note             VARCHAR(300) NULL,
  status           ENUM('open','resolved','dismissed') NOT NULL DEFAULT 'open',
  handled_by       BIGINT UNSIGNED NULL,
  handled_at       DATETIME NULL,
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_report_once (review_id, reporter_user_id),   -- 1 user melapor 1x per review
  KEY idx_reports_status (status, created_at),
  CONSTRAINT fk_reports_review   FOREIGN KEY (review_id)        REFERENCES reviews(id) ON DELETE CASCADE,
  CONSTRAINT fk_reports_reporter FOREIGN KEY (reporter_user_id) REFERENCES users(id)   ON DELETE CASCADE,
  CONSTRAINT fk_reports_handler  FOREIGN KEY (handled_by)       REFERENCES users(id)   ON DELETE SET NULL
) ENGINE=InnoDB;

