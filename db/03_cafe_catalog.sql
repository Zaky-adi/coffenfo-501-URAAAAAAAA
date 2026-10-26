-- =====================================================================
-- Modul 03 — Katalog Cafe
-- Use case: profil cafe, galeri, jam buka, spesialisasi kopi, fasilitas, cafe pilihan, pendaftaran mitra.
-- Figma: Landing Page, Filter Bar, Detail Cafe, form "Jadi mitra Coffenfo".
-- Dependensi: modul 01, 02.
-- =====================================================================

USE coffenfo;

CREATE TABLE IF NOT EXISTS cafes (
  id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  public_id         CHAR(36) NOT NULL DEFAULT (UUID()),
  owner_user_id     BIGINT UNSIGNED NULL,
  name              VARCHAR(150) NOT NULL,
  slug              VARCHAR(170) NOT NULL,
  tagline           VARCHAR(160) NULL,                 -- "Specialty Coffee & French Bistro"
  description       TEXT NULL,
  coffee_note       VARCHAR(255) NULL,                 -- catatan di kotak "Kenali kopimu"
  address           VARCHAR(255) NOT NULL,
  area_id           SMALLINT UNSIGNED NOT NULL,
  latitude          DECIMAL(9,6) NOT NULL,
  longitude         DECIMAL(9,6) NOT NULL,
  phone             VARCHAR(20) NULL,
  instagram_url     VARCHAR(255) NULL,
  price_range       TINYINT UNSIGNED NOT NULL DEFAULT 2,   -- 1=$ 2=$$ 3=$$$ 4=$$$$
  has_self_roasting TINYINT(1) NOT NULL DEFAULT 0,
  accepts_orders    TINYINT(1) NOT NULL DEFAULT 0,         -- toggle "Online – Menerima Pesanan"
  status            ENUM('draft','pending','active','suspended') NOT NULL DEFAULT 'draft',
  avg_rating        DECIMAL(3,2) NOT NULL DEFAULT 0.00,    -- cache dari reviews
  review_count      INT UNSIGNED NOT NULL DEFAULT 0,       -- cache dari reviews
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cafes_public_id (public_id),
  UNIQUE KEY uq_cafes_slug (slug),
  KEY idx_cafes_status_area (status, area_id),
  KEY idx_cafes_price (price_range),
  KEY idx_cafes_rating (avg_rating, review_count),
  KEY idx_cafes_geo (latitude, longitude),
  KEY idx_cafes_owner (owner_user_id),
  FULLTEXT KEY ft_cafes_search (name, tagline),
  CONSTRAINT chk_cafes_price  CHECK (price_range BETWEEN 1 AND 4),
  CONSTRAINT chk_cafes_rating CHECK (avg_rating BETWEEN 0 AND 5),
  CONSTRAINT chk_cafes_lat    CHECK (latitude  BETWEEN -90  AND 90),
  CONSTRAINT chk_cafes_lng    CHECK (longitude BETWEEN -180 AND 180),
  CONSTRAINT fk_cafes_owner FOREIGN KEY (owner_user_id) REFERENCES users(id) ON DELETE SET NULL,
  CONSTRAINT fk_cafes_area  FOREIGN KEY (area_id)       REFERENCES areas(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cafe_photos (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cafe_id    BIGINT UNSIGNED NOT NULL,
  url        VARCHAR(500) NOT NULL,
  alt_text   VARCHAR(150) NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  is_cover   TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_cafe_photos_cafe (cafe_id, sort_order),
  CONSTRAINT fk_cafe_photos_cafe FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- day_of_week ISO: 1=Senin ... 7=Minggu. close_time < open_time berarti tutup lewat tengah malam.
CREATE TABLE IF NOT EXISTS cafe_opening_hours (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cafe_id     BIGINT UNSIGNED NOT NULL,
  day_of_week TINYINT UNSIGNED NOT NULL,
  open_time   TIME NULL,
  close_time  TIME NULL,
  is_closed   TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_hours_cafe_day (cafe_id, day_of_week),
  CONSTRAINT chk_hours_day  CHECK (day_of_week BETWEEN 1 AND 7),
  CONSTRAINT chk_hours_time CHECK (is_closed = 1 OR (open_time IS NOT NULL AND close_time IS NOT NULL)),
  CONSTRAINT fk_hours_cafe FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- Junction: spesialisasi kopi & fasilitas ----------
CREATE TABLE IF NOT EXISTS cafe_bean_types (
  cafe_id BIGINT UNSIGNED NOT NULL, bean_type_id TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (cafe_id, bean_type_id), KEY idx_cbt_bean (bean_type_id),
  CONSTRAINT fk_cbt_cafe FOREIGN KEY (cafe_id)      REFERENCES cafes(id)      ON DELETE CASCADE,
  CONSTRAINT fk_cbt_bean FOREIGN KEY (bean_type_id) REFERENCES bean_types(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cafe_species (
  cafe_id BIGINT UNSIGNED NOT NULL, species_id TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (cafe_id, species_id), KEY idx_cs_species (species_id),
  CONSTRAINT fk_cs_cafe    FOREIGN KEY (cafe_id)    REFERENCES cafes(id)          ON DELETE CASCADE,
  CONSTRAINT fk_cs_species FOREIGN KEY (species_id) REFERENCES coffee_species(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cafe_roast_levels (
  cafe_id BIGINT UNSIGNED NOT NULL, roast_level_id TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (cafe_id, roast_level_id), KEY idx_crl_roast (roast_level_id),
  CONSTRAINT fk_crl_cafe  FOREIGN KEY (cafe_id)        REFERENCES cafes(id)        ON DELETE CASCADE,
  CONSTRAINT fk_crl_roast FOREIGN KEY (roast_level_id) REFERENCES roast_levels(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cafe_brew_methods (
  cafe_id BIGINT UNSIGNED NOT NULL, brew_method_id TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (cafe_id, brew_method_id), KEY idx_cbm_brew (brew_method_id),
  CONSTRAINT fk_cbm_cafe FOREIGN KEY (cafe_id)        REFERENCES cafes(id)        ON DELETE CASCADE,
  CONSTRAINT fk_cbm_brew FOREIGN KEY (brew_method_id) REFERENCES brew_methods(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cafe_facilities (
  cafe_id BIGINT UNSIGNED NOT NULL, facility_id SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (cafe_id, facility_id), KEY idx_cf_facility (facility_id),
  CONSTRAINT fk_cf_cafe     FOREIGN KEY (cafe_id)     REFERENCES cafes(id)      ON DELETE CASCADE,
  CONSTRAINT fk_cf_facility FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- "Cafe pilihan minggu ini" (dikurasi admin)
CREATE TABLE IF NOT EXISTS featured_cafes (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cafe_id    BIGINT UNSIGNED NOT NULL,
  label      VARCHAR(60) NULL,
  starts_on  DATE NOT NULL,
  ends_on    DATE NOT NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  created_by BIGINT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_featured_period (starts_on, ends_on),
  CONSTRAINT chk_featured_period CHECK (ends_on >= starts_on),
  CONSTRAINT fk_featured_cafe FOREIGN KEY (cafe_id)    REFERENCES cafes(id) ON DELETE CASCADE,
  CONSTRAINT fk_featured_by   FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Form "Punya cafe di Batam? Jadi mitra Coffenfo" (publik, tanpa login)
CREATE TABLE IF NOT EXISTS partnership_requests (
  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cafe_name   VARCHAR(150) NOT NULL,
  owner_name  VARCHAR(120) NOT NULL,
  whatsapp    VARCHAR(20) NOT NULL,                   -- data pribadi, batasi akses
  area_id     SMALLINT UNSIGNED NOT NULL,
  status      ENUM('new','contacted','approved','rejected') NOT NULL DEFAULT 'new',
  admin_note  VARCHAR(500) NULL,
  reviewed_by BIGINT UNSIGNED NULL,
  ip_address  VARCHAR(45) NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_partner_status (status, created_at),
  CONSTRAINT fk_partner_area FOREIGN KEY (area_id)     REFERENCES areas(id) ON DELETE RESTRICT,
  CONSTRAINT fk_partner_by   FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

