ALTER TABLE `penduduk` ADD `kode_posyandu` INT DEFAULT NULL;

CREATE TABLE posyandu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kecamatan_id INT NOT NULL,
    kelurahan_id INT NOT NULL,
    lingkungan_id INT DEFAULT NULL,
    CONSTRAINT fk_posyandu_kecamatan_id FOREIGN KEY (kecamatan_id) REFERENCES kecamatan(id) ON DELETE CASCADE,
    CONSTRAINT fk_posyandu_kelurahan_id FOREIGN KEY (kelurahan_id) REFERENCES kelurahan(id) ON DELETE CASCADE,
    CONSTRAINT fk_posyandu_lingkungan_id FOREIGN KEY (lingkungan_id) REFERENCES lingkungan(id) ON DELETE CASCADE
);

CREATE TABLE imunisasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posyandu_id INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    usia VARCHAR(50) NOT NULL,
    berat VARCHAR(50) NOT NULL,
    tinggi VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ibu_hamil (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posyandu_id INT NOT NULL,
    NIK VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    nama_suami VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    kelurahan VARCHAR(100) NOT NULL,
    status_peserta VARCHAR(50) NOT NULL,
    hpht VARCHAR(50) NOT NULL,
    usia_kandungan VARCHAR(20) NOT NULL,
    berat VARCHAR(20) NOT NULL,
    tinggi VARCHAR(20) NOT NULL,
    lila VARCHAR(20) NOT NULL,
    hasil VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kegiatan_kb (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posyandu_id INT NOT NULL,
    NIK VARCHAR(50) NULL,
    nama VARCHAR(100) NOT NULL,
    nama_pasangan VARCHAR(100) NOT NULL,
    usia VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    kelurahan TEXT NOT NULL,
    jumlah_anak INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    jenis VARCHAR(50) NOT NULL,
    keterangan TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);