-- =====================================================================
-- Modul 12 — Rekomendasi Personal (FYP: tiap user punya feed berbeda)
-- Use case: sistem belajar dari pencarian, cafe yang dibuka, wishlist, review, pesanan, dan lokasi.
-- Dependensi: modul 02, 03, 05, 06.
-- =====================================================================

USE coffenfo;

-- Persetujuan & preferensi privasi (dibuat saat registrasi)
CREATE TABLE IF NOT EXISTS user_privacy_settings (
  user_id                 BIGINT UNSIGNED NOT NULL,
  personalization_enabled TINYINT(1) NOT NULL DEFAULT 0,
  location_enabled        TINYINT(1) NOT NULL DEFAULT 0,
  consent_version         VARCHAR(20) NULL,           -- versi teks persetujuan yang disetujui
  consented_at            DATETIME NULL,
  updated_at              DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  CONSTRAINT fk_privacy_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Lokasi terakhir saja (BUKAN riwayat)
CREATE TABLE IF NOT EXISTS user_locations (
  user_id    BIGINT UNSIGNED NOT NULL,
  latitude   DECIMAL(9,6) NOT NULL,                   -- API membulatkan ke 3 desimal sebelum simpan
  longitude  DECIMAL(9,6) NOT NULL,
  area_id    SMALLINT UNSIGNED NULL,
  accuracy_m SMALLINT UNSIGNED NULL,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  CONSTRAINT chk_uloc_lat CHECK (latitude  BETWEEN -90  AND 90),
  CONSTRAINT chk_uloc_lng CHECK (longitude BETWEEN -180 AND 180),
  CONSTRAINT fk_uloc_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_uloc_area FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Lapis 1: event mentah (volume tinggi, retensi 90 hari)
CREATE TABLE IF NOT EXISTS user_events (
  id             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id        BIGINT UNSIGNED NOT NULL,
  event_type     ENUM('impression','view','click','search','search_click','wishlist_add','wishlist_remove',
                      'directions_click','share','dismiss','review','order') NOT NULL,
  cafe_id        BIGINT UNSIGNED NULL,
  query_text     VARCHAR(100) NULL,                   -- untuk event 'search'
  surface        VARCHAR(30) NULL,                    -- home_for_you, home_nearby, home_popular, search, detail_similar, wishlist
  position       TINYINT UNSIGNED NULL,               -- urutan kartu saat tampil (untuk mengukur bias posisi)
  rec_request_id CHAR(36) NULL,                       -- menghubungkan impression ↔ klik dari satu feed
  model_version  VARCHAR(20) NULL,
  value_num      DECIMAL(4,2) NULL,                   -- mis. bintang pada event 'review'
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_events_user_time (user_id, created_at),
  KEY idx_events_user_type (user_id, event_type, created_at),
  KEY idx_events_cafe_type (cafe_id, event_type, created_at),
  KEY idx_events_request   (rec_request_id),
  CONSTRAINT fk_events_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_events_cafe FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Lapis 2: profil selera per user (hasil agregasi job)
CREATE TABLE IF NOT EXISTS user_taste_weights (
  user_id        BIGINT UNSIGNED NOT NULL,
  feature_type   ENUM('bean_type','roast_level','brew_method','species','facility','area',
                      'price_range','menu_category','keyword') NOT NULL,
  feature_key    VARCHAR(60) NOT NULL,                -- id master (sebagai teks) atau kata kunci
  weight         DECIMAL(8,4) NOT NULL,               -- ternormalisasi per feature_type (−1 s/d 1)
  evidence_count SMALLINT UNSIGNED NOT NULL DEFAULT 0,-- jumlah event pendukung (untuk tingkat keyakinan)
  updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, feature_type, feature_key),
  KEY idx_taste_user_weight (user_id, weight),
  CONSTRAINT chk_taste_weight CHECK (weight BETWEEN -1 AND 1),
  CONSTRAINT fk_taste_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Lapis 3a: kemiripan antar cafe (item-item), dihitung malam hari
CREATE TABLE IF NOT EXISTS cafe_similarity (
  cafe_id         BIGINT UNSIGNED NOT NULL,
  similar_cafe_id BIGINT UNSIGNED NOT NULL,
  score           DECIMAL(6,5) NOT NULL,              -- 0..1
  method          ENUM('content','collab','hybrid') NOT NULL DEFAULT 'hybrid',
  computed_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cafe_id, similar_cafe_id),
  KEY idx_sim_rank (cafe_id, score DESC),
  CONSTRAINT chk_sim_diff  CHECK (cafe_id <> similar_cafe_id),
  CONSTRAINT chk_sim_score CHECK (score BETWEEN 0 AND 1),
  CONSTRAINT fk_sim_cafe    FOREIGN KEY (cafe_id)         REFERENCES cafes(id) ON DELETE CASCADE,
  CONSTRAINT fk_sim_similar FOREIGN KEY (similar_cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Lapis 3b: skor kandidat per user (top ~100 per user, tanpa komponen jarak real-time)
CREATE TABLE IF NOT EXISTS user_cafe_scores (
  user_id       BIGINT UNSIGNED NOT NULL,
  cafe_id       BIGINT UNSIGNED NOT NULL,
  score         DECIMAL(7,5) NOT NULL,
  reason_code   VARCHAR(40) NULL,                     -- matches_taste, similar_to_wishlist, because_you_searched, popular_in_area, trending, explore
  reason_ref    VARCHAR(60) NULL,                     -- mis. nama fitur ("V60") atau public_id cafe pemicu
  model_version VARCHAR(20) NOT NULL,
  computed_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, cafe_id),
  KEY idx_scores_rank (user_id, score DESC),
  CONSTRAINT fk_scores_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_scores_cafe FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- "Tidak tertarik" (sinyal negatif eksplisit)
CREATE TABLE IF NOT EXISTS user_hidden_cafes (
  user_id    BIGINT UNSIGNED NOT NULL,
  cafe_id    BIGINT UNSIGNED NOT NULL,
  reason     ENUM('not_interested','closed','bad_experience','other') NOT NULL DEFAULT 'not_interested',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, cafe_id),
  CONSTRAINT fk_hidden_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_hidden_cafe FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Versi model/parameter (untuk A/B test dan rollback cepat)
CREATE TABLE IF NOT EXISTS reco_model_versions (
  id              SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  version         VARCHAR(20) NOT NULL,
  description     VARCHAR(200) NULL,
  params          JSON NOT NULL,
  traffic_percent TINYINT UNSIGNED NOT NULL DEFAULT 100,   -- porsi user (0-100) yang memakai versi ini
  is_active       TINYINT(1) NOT NULL DEFAULT 0,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_reco_version (version),
  CONSTRAINT chk_reco_traffic CHECK (traffic_percent BETWEEN 0 AND 100)
) ENGINE=InnoDB;

INSERT IGNORE INTO reco_model_versions (version, description, params, traffic_percent, is_active) VALUES
('v1-content', 'Tahap B: selera + jarak + populer + mirip yang disukai',
 '{"half_life_days":30,
   "event_weights":{"view":1,"click":2,"search":1,"search_click":2,"directions_click":4,"share":3,
                    "wishlist_add":5,"wishlist_remove":-3,"review_pos":6,"review_neg":-4,"order":8,
                    "dismiss":-6,"impression":-0.05},
   "feature_weights":{"brew_method":0.25,"bean_type":0.20,"roast_level":0.20,"facility":0.15,"price_range":0.10,"area":0.10},
   "score_weights":{"taste":0.40,"proximity":0.25,"popularity":0.15,"similar_to_liked":0.15,"freshness":0.05},
   "cold_start_score_weights":{"taste":0.15,"proximity":0.35,"popularity":0.40,"similar_to_liked":0.05,"freshness":0.05},
   "cold_start_min_events":10,
   "distance_decay_km":3,
   "explore_ratio":0.10,
   "diversity_lambda":0.7,
   "max_events_per_cafe_per_day":3,
   "candidate_pool_size":100}', 100, 1);

-- Backfill untuk user yang sudah ada sebelum modul ini (default tidak setuju)
INSERT IGNORE INTO user_privacy_settings (user_id) SELECT id FROM users;

-- Retensi event mentah 90 hari (hapus bertahap agar tidak mengunci tabel)
CREATE EVENT IF NOT EXISTS ev_purge_user_events
  ON SCHEDULE EVERY 1 HOUR
  DO DELETE FROM user_events WHERE created_at < UTC_TIMESTAMP() - INTERVAL 90 DAY LIMIT 50000;

