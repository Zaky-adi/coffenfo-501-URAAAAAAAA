-- =====================================================================
-- Modul 04 — Menu
-- Use case: daftar menu + harga per cafe, filter Kopi / Non-Kopi / Cemilan / Makanan Berat.
-- Figma: tab "Katalog Menu", Katalog Menu Digital (pemilik).
-- Dependensi: modul 01, 03.
-- =====================================================================

USE coffenfo;

CREATE TABLE IF NOT EXISTS menu_items (
  id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  public_id    CHAR(36) NOT NULL DEFAULT (UUID()),
  cafe_id      BIGINT UNSIGNED NOT NULL,
  category_id  TINYINT UNSIGNED NOT NULL,
  name         VARCHAR(150) NOT NULL,
  description  VARCHAR(300) NULL,
  price        INT UNSIGNED NOT NULL,               -- rupiah utuh
  image_url    VARCHAR(500) NULL,
  is_available TINYINT(1) NOT NULL DEFAULT 1,       -- stok habis hari ini
  is_active    TINYINT(1) NOT NULL DEFAULT 1,       -- soft delete
  sort_order   SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_menu_public_id (public_id),
  KEY idx_menu_cafe_cat (cafe_id, category_id, is_active, sort_order),
  FULLTEXT KEY ft_menu_name (name),
  CONSTRAINT chk_menu_price CHECK (price > 0),
  CONSTRAINT fk_menu_cafe     FOREIGN KEY (cafe_id)     REFERENCES cafes(id)           ON DELETE CASCADE,
  CONSTRAINT fk_menu_category FOREIGN KEY (category_id) REFERENCES menu_categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

