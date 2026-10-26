-- =====================================================================
-- Modul 01 — Master Data
-- Use case: pilihan filter dan data referensi (dikelola admin lewat menu "Kelola Beans & Roasting").
-- Figma: Filter Bar, Detail Cafe ("Kenali kopimu", Fasilitas), Daftar (preferensi), form Mitra (Area).
-- =====================================================================

USE coffenfo;

CREATE TABLE IF NOT EXISTS areas (
  id         SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(80) NOT NULL,
  slug       VARCHAR(80) NOT NULL,
  latitude   DECIMAL(9,6) NULL,          -- titik tengah area (isi manual, dipakai jika GPS user ditolak)
  longitude  DECIMAL(9,6) NULL,
  is_active  TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_areas_name (name),
  UNIQUE KEY uq_areas_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bean_types (            -- Single Origin / Blend
  id         TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(60) NOT NULL,
  slug       VARCHAR(60) NOT NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  is_active  TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_bean_types_name (name),
  UNIQUE KEY uq_bean_types_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS coffee_species (        -- Arabika / Robusta / ...
  id         TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(60) NOT NULL,
  slug       VARCHAR(60) NOT NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  is_active  TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_coffee_species_name (name),
  UNIQUE KEY uq_coffee_species_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS roast_levels (          -- Light / Medium / Dark
  id         TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(60) NOT NULL,
  slug       VARCHAR(60) NOT NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  is_active  TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_roast_levels_name (name),
  UNIQUE KEY uq_roast_levels_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS brew_methods (          -- "penyajian": V60, Espresso, Cold Brew, ...
  id         TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(60) NOT NULL,
  slug       VARCHAR(60) NOT NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  is_active  TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_brew_methods_name (name),
  UNIQUE KEY uq_brew_methods_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS facilities (
  id         SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(80) NOT NULL,
  slug       VARCHAR(80) NOT NULL,
  icon       VARCHAR(40) NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  is_active  TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_facilities_name (name),
  UNIQUE KEY uq_facilities_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS menu_categories (       -- Kopi / Non-Kopi / Cemilan / Makanan Berat
  id         TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name       VARCHAR(60) NOT NULL,
  slug       VARCHAR(60) NOT NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  is_active  TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_menu_categories_name (name),
  UNIQUE KEY uq_menu_categories_slug (slug)
) ENGINE=InnoDB;

-- ---------- SEED ----------
INSERT IGNORE INTO areas (name, slug) VALUES
 ('Batam Center','batam-center'),('Nagoya','nagoya'),('Jodoh','jodoh'),
 ('Batu Ampar','batu-ampar'),('Lubuk Baja','lubuk-baja'),('Sekupang','sekupang'),
 ('Batu Aji','batu-aji'),('Sagulung','sagulung'),('Bengkong','bengkong'),
 ('Tiban','tiban'),('Nongsa','nongsa'),('Tanjung Uncang','tanjung-uncang');

INSERT IGNORE INTO bean_types (name, slug, sort_order) VALUES
 ('Single Origin','single-origin',1),('Blend','blend',2);

INSERT IGNORE INTO coffee_species (name, slug, sort_order) VALUES
 ('Arabika','arabika',1),('Robusta','robusta',2),('Liberika','liberika',3),('Excelsa','excelsa',4);

INSERT IGNORE INTO roast_levels (name, slug, sort_order) VALUES
 ('Light','light',1),('Medium','medium',2),('Dark','dark',3);

INSERT IGNORE INTO brew_methods (name, slug, sort_order) VALUES
 ('Espresso','espresso',1),('V60','v60',2),('Aeropress','aeropress',3),
 ('Japanese Style','japanese-style',4),('Cold Brew','cold-brew',5),
 ('French Press','french-press',6),('Vietnam Drip','vietnam-drip',7),
 ('Kopi Tubruk','kopi-tubruk',8),('Syphon','syphon',9);

INSERT IGNORE INTO facilities (name, slug, icon, sort_order) VALUES
 ('Wi-Fi Gratis','wifi','wifi',1),('Stopkontak','power-outlet','plug',2),
 ('Ruangan ber-AC','ac','snowflake',3),('Area Outdoor','outdoor','sun',4),
 ('Smoking Area','smoking','cigarette',5),('Parkir Kendaraan','parking','car',6),
 ('Cocok untuk Kerja','work-friendly','laptop',7),('Musholla','prayer-room','moon',8),
 ('Toilet','toilet','toilet',9),('Pet Friendly','pet-friendly','paw',10);

INSERT IGNORE INTO menu_categories (name, slug, sort_order) VALUES
 ('Kopi','kopi',1),('Non-Kopi','non-kopi',2),('Cemilan','cemilan',3),('Makanan Berat','makanan-berat',4);

