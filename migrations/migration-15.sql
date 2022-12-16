CREATE TABLE imunisasi_vaksin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imunisasi_id INT NOT NULL,
    penduduk_id INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jenis VARCHAR(50) NOT NULL
);