-- =====================================================================
-- Modul 08 — Order & Checkout
-- Use case: keranjang, checkout (dine-in/takeaway), pembayaran e-wallet/VA, pesanan masuk untuk pemilik cafe.
-- Figma: Checkout web, Tampilan Pemilik Cafe (Pesanan), Pembayaran Berhasil.
-- Dependensi: modul 04, 07.
-- =====================================================================

USE coffenfo;

CREATE TABLE IF NOT EXISTS carts (
  id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id    BIGINT UNSIGNED NOT NULL,
  cafe_id    BIGINT UNSIGNED NOT NULL,                 -- keranjang hanya untuk 1 cafe
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cart_user (user_id),
  CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_cafe FOREIGN KEY (cafe_id) REFERENCES cafes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cart_items (
  id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  cart_id      BIGINT UNSIGNED NOT NULL,
  menu_item_id BIGINT UNSIGNED NOT NULL,
  quantity     TINYINT UNSIGNED NOT NULL DEFAULT 1,
  note         VARCHAR(200) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_cart_item (cart_id, menu_item_id),
  CONSTRAINT chk_cart_qty CHECK (quantity BETWEEN 1 AND 99),
  CONSTRAINT fk_cartitem_cart FOREIGN KEY (cart_id)      REFERENCES carts(id)      ON DELETE CASCADE,
  CONSTRAINT fk_cartitem_menu FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id                    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  public_id             CHAR(36) NOT NULL DEFAULT (UUID()),
  order_code            VARCHAR(20) NOT NULL,                  -- tampil di UI, mis. ORD-251025-0001
  user_id               BIGINT UNSIGNED NOT NULL,
  cafe_id               BIGINT UNSIGNED NOT NULL,
  order_type            ENUM('dine_in','takeaway') NOT NULL,
  table_number          VARCHAR(10) NULL,                      -- wajib jika dine_in (dicek di API)
  customer_note         VARCHAR(300) NULL,
  status                ENUM('pending_payment','waiting_confirmation','processing','ready','completed','cancelled')
                        NOT NULL DEFAULT 'pending_payment',
  subtotal              INT UNSIGNED NOT NULL,
  service_fee           INT UNSIGNED NOT NULL DEFAULT 0,
  discount_amount       INT UNSIGNED NOT NULL DEFAULT 0,
  total_amount          INT UNSIGNED NOT NULL,
  voucher_redemption_id BIGINT UNSIGNED NULL,
  est_prep_min          TINYINT UNSIGNED NULL,                 -- estimasi "12–15 menit"
  est_prep_max          TINYINT UNSIGNED NULL,
  cancel_reason         VARCHAR(255) NULL,
  paid_at               DATETIME NULL,
  accepted_at           DATETIME NULL,
  completed_at          DATETIME NULL,
  created_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at            DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_orders_public_id (public_id),
  UNIQUE KEY uq_orders_code (order_code),
  KEY idx_orders_cafe_status (cafe_id, status, created_at),
  KEY idx_orders_user (user_id, created_at),
  CONSTRAINT chk_orders_total CHECK (total_amount + discount_amount = subtotal + service_fee),
  CONSTRAINT fk_orders_user    FOREIGN KEY (user_id)               REFERENCES users(id)               ON DELETE RESTRICT,
  CONSTRAINT fk_orders_cafe    FOREIGN KEY (cafe_id)               REFERENCES cafes(id)               ON DELETE RESTRICT,
  CONSTRAINT fk_orders_voucher FOREIGN KEY (voucher_redemption_id) REFERENCES voucher_redemptions(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id     BIGINT UNSIGNED NOT NULL,
  menu_item_id BIGINT UNSIGNED NULL,
  item_name    VARCHAR(150) NOT NULL,                  -- snapshot
  unit_price   INT UNSIGNED NOT NULL,                  -- snapshot
  quantity     TINYINT UNSIGNED NOT NULL,
  note         VARCHAR(200) NULL,
  line_total   INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  KEY idx_order_items_order (order_id),
  CONSTRAINT chk_oi_qty   CHECK (quantity BETWEEN 1 AND 99),
  CONSTRAINT chk_oi_total CHECK (line_total = unit_price * quantity),
  CONSTRAINT fk_oi_order FOREIGN KEY (order_id)     REFERENCES orders(id)     ON DELETE CASCADE,
  CONSTRAINT fk_oi_menu  FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_status_history (
  id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id        BIGINT UNSIGNED NOT NULL,
  from_status     VARCHAR(30) NULL,
  to_status       VARCHAR(30) NOT NULL,
  changed_by      BIGINT UNSIGNED NULL,                -- NULL = sistem/webhook
  note            VARCHAR(255) NULL,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_osh_order (order_id, created_at),
  CONSTRAINT fk_osh_order FOREIGN KEY (order_id)   REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_osh_user  FOREIGN KEY (changed_by) REFERENCES users(id)  ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS payments (
  id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  public_id        CHAR(36) NOT NULL DEFAULT (UUID()),
  order_id         BIGINT UNSIGNED NOT NULL,
  method           ENUM('ewallet','bank_transfer') NOT NULL,
  channel          VARCHAR(30) NOT NULL,               -- qris, gopay, ovo, shopeepay, bca_va, mandiri_va
  provider         VARCHAR(30) NOT NULL,               -- nama payment gateway
  provider_ref     VARCHAR(100) NULL,                  -- ID transaksi di gateway
  amount           INT UNSIGNED NOT NULL,
  status           ENUM('pending','paid','failed','expired','refunded') NOT NULL DEFAULT 'pending',
  expires_at       DATETIME NULL,
  paid_at          DATETIME NULL,
  callback_payload JSON NULL,                          -- bersihkan data pribadi, retensi 90 hari
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_payments_public_id (public_id),
  UNIQUE KEY uq_payments_provider_ref (provider, provider_ref),
  KEY idx_payments_order (order_id, status),
  CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Menutup relasi melingkar dari modul 07
ALTER TABLE voucher_redemptions
  ADD COLUMN IF NOT EXISTS used_order_id BIGINT UNSIGNED NULL,
  ADD CONSTRAINT fk_redeem_order FOREIGN KEY (used_order_id) REFERENCES orders(id) ON DELETE SET NULL;

