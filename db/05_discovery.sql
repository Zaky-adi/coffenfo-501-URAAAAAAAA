-- =====================================================================
-- Modul 05 — Discovery (Wishlist, Terakhir Dikunjungi, Terakhir Dicari)
-- Use case: halaman awal personal.
-- Figma: ikon hati pada kartu cafe, search bar.
-- Dependensi: modul 02, 03.
-- =====================================================================

USE coffenfo;

CREATE TABLE IF NOT EXISTS wishlists (
  user_id    BIGINT UNSIGNED NOT NULL,
  cafe_id    BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, cafe_id),
  KEY idx_wishlist_cafe (cafe_id),
  CONSTRAINT fk_wish_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_wish_cafe FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Satu baris per (user, cafe). Upsert saat user membuka halaman detail cafe.
CREATE TABLE IF NOT EXISTS user_cafe_views (
  user_id         BIGINT UNSIGNED NOT NULL,
  cafe_id         BIGINT UNSIGNED NOT NULL,
  view_count      INT UNSIGNED NOT NULL DEFAULT 1,
  first_viewed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_viewed_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, cafe_id),
  KEY idx_views_user_recent (user_id, last_viewed_at),
  CONSTRAINT fk_views_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_views_cafe FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user_search_history (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    BIGINT UNSIGNED NOT NULL,
  keyword    VARCHAR(100) NOT NULL,
  cafe_id    BIGINT UNSIGNED NULL,                 -- cafe yang dipilih dari hasil pencarian (jika ada)
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_search_user_recent (user_id, created_at),
  CONSTRAINT fk_search_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_search_cafe FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE SET NULL
) ENGINE=InnoDB;

