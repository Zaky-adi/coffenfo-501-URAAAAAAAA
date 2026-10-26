-- =====================================================================
-- Modul 11 — Hak Akses Database
-- Tujuan: prinsip least privilege. Jalankan sebagai admin MySQL setelah semua tabel dibuat, termasuk modul 12 dan 13.
-- JANGAN COMMIT PASSWORD ASLI; ISI DARI SECRET MANAGER
-- =====================================================================

-- 1) User aplikasi (runtime)
CREATE USER IF NOT EXISTS 'coffenfo_app'@'%'
  IDENTIFIED BY '<PASSWORD_KUAT_1>' REQUIRE SSL;
GRANT SELECT, INSERT, UPDATE, EXECUTE ON coffenfo.* TO 'coffenfo_app'@'%';

-- DELETE hanya untuk tabel yang memang boleh dihapus fisik
GRANT DELETE ON coffenfo.wishlists             TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.user_search_history   TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.user_cafe_views       TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.review_helpful_votes  TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.review_photos         TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.reviews               TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.refresh_tokens        TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.user_tokens           TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.cafe_photos           TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.cafe_opening_hours    TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.cafe_bean_types       TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.cafe_species          TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.cafe_roast_levels     TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.cafe_brew_methods     TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.cafe_facilities       TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.carts                 TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.cart_items            TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.notifications         TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.user_devices          TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.notification_preferences TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.push_outbox           TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.uploads               TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.idempotency_keys      TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.user_events           TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.user_taste_weights    TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.user_cafe_scores      TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.user_hidden_cafes     TO 'coffenfo_app'@'%';
GRANT DELETE ON coffenfo.user_locations        TO 'coffenfo_app'@'%';

-- 2) User migrasi (hanya dipakai saat deploy)
CREATE USER IF NOT EXISTS 'coffenfo_migrate'@'%'
  IDENTIFIED BY '<PASSWORD_KUAT_2>' REQUIRE SSL;
GRANT ALL PRIVILEGES ON coffenfo.* TO 'coffenfo_migrate'@'%';

-- 3) User baca-saja (laporan/analitik) tanpa kolom rahasia
CREATE USER IF NOT EXISTS 'coffenfo_ro'@'%'
  IDENTIFIED BY '<PASSWORD_KUAT_3>' REQUIRE SSL;
GRANT SELECT ON coffenfo.cafes   TO 'coffenfo_ro'@'%';
GRANT SELECT ON coffenfo.reviews TO 'coffenfo_ro'@'%';
GRANT SELECT ON coffenfo.orders  TO 'coffenfo_ro'@'%';

FLUSH PRIVILEGES;

