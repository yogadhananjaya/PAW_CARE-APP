-- File untuk Stored Procedure, UDF, dan Trigger
-- Database: paw_care

USE paw_care;

-- -------------------------------------------------------------
-- 1. STORED PROCEDURE
-- -------------------------------------------------------------
-- Prosedur untuk adopsi hewan
-- Mengubah status hewan dan memasukkan data ke transaksi_adopsi
DROP PROCEDURE IF EXISTS sp_adopsi_hewan;
DELIMITER //
CREATE PROCEDURE sp_adopsi_hewan(
    IN input_id_hewan INT,
    IN input_id_pengadopsi INT,
    IN input_id_pengguna INT
)
BEGIN
    -- Update status hewan menjadi Diadopsi
    UPDATE hewan 
    SET status_adopsi = 'Diadopsi' 
    WHERE id_hewan = input_id_hewan;

    -- Masukkan data transaksi adopsi baru
    INSERT INTO transaksi_adopsi (
        kode_transaksi_adopsi, 
        id_hewan, 
        id_pengadopsi, 
        id_pengguna, 
        tanggal_adopsi, 
        status_kontrak
    ) VALUES (
        CONCAT('TX', LPAD(FLOOR(RAND() * 9000 + 1000), 4, '0')),
        input_id_hewan,
        input_id_pengadopsi,
        input_id_pengguna,
        CURDATE(),
        'Draft'
    );
END;
//
DELIMITER ;


-- -------------------------------------------------------------
-- 2. USER DEFINED FUNCTION (UDF)
-- -------------------------------------------------------------
-- Fungsi untuk menghitung umur hewan dalam bulan
DROP FUNCTION IF EXISTS f_hitung_umur_bulan;
DELIMITER //
CREATE FUNCTION f_hitung_umur_bulan(tanggal_lahir DATE)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE hasil_umur INT;
    
    -- Hitung selisih bulan dari tanggal lahir sampai sekarang
    SET hasil_umur = TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE());
    
    RETURN hasil_umur;
END;
//
DELIMITER ;


-- -------------------------------------------------------------
-- 3. TRIGGER
-- -------------------------------------------------------------
-- Trigger untuk mengurangi stok vaksin saat ada riwayat vaksinasi baru
DROP TRIGGER IF EXISTS tr_kurangi_stok_vaksin;
DELIMITER //
CREATE TRIGGER tr_kurangi_stok_vaksin
AFTER INSERT ON riwayat_kesehatan
FOR EACH ROW
BEGIN
    -- Jika tipe riwayat adalah Vaksinasi dan ID vaksin diisi
    IF NEW.tipe = 'Vaksinasi' AND NEW.id_vaksin IS NOT NULL THEN
        UPDATE vaksin 
        SET stok = stok - 1 
        WHERE id_vaksin = NEW.id_vaksin;
    END IF;
END;
//
DELIMITER ;
