-- =====================================================================
-- Modul 10 — View & Prosedur
-- Use case: hitung ulang rating cafe, distribusi bintang untuk halaman Review & Rating.
-- Dependensi: modul 06.
-- =====================================================================

USE coffenfo;

-- Hitung ulang cache rating satu cafe. Dipanggil API setelah review disetujui/ditolak/diedit/dihapus.
DROP PROCEDURE IF EXISTS sp_recalc_cafe_rating;
DELIMITER //
CREATE PROCEDURE sp_recalc_cafe_rating(IN p_cafe_id BIGINT UNSIGNED)
BEGIN
  UPDATE cafes c
  LEFT JOIN (
    SELECT cafe_id, ROUND(AVG(rating), 2) AS avg_r, COUNT(*) AS cnt
    FROM reviews
    WHERE cafe_id = p_cafe_id AND status IN ('approved','reported')
    GROUP BY cafe_id
  ) r ON r.cafe_id = c.id
  SET c.avg_rating   = COALESCE(r.avg_r, 0),
      c.review_count = COALESCE(r.cnt, 0)
  WHERE c.id = p_cafe_id;
END//
DELIMITER ;

-- Distribusi bintang (bar 5..1 di Figma). Persentase dihitung di API: total / review_count.
CREATE OR REPLACE VIEW v_cafe_rating_distribution AS
SELECT cafe_id, rating, COUNT(*) AS total
FROM reviews
WHERE status IN ('approved','reported')
GROUP BY cafe_id, rating;

