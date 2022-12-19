CREATE TABLE pemantauan_gizi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posyandu_id INT NOT NULL,
    penduduk_id VARCHAR(50) NULL,
    nama VARCHAR(100) NOT NULL,
    nama_orangtua VARCHAR(100) NOT NULL,
    jenis_kelamin VARCHAR(100) NOT NULL,
    tanggal_lahir VARCHAR(50) NOT NULL,
    usia VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    tanggal_pemeriksaan date NOT NULL,
    status_pemantauan VARCHAR(50) NOT NULL,
    berat VARCHAR(10) NOT NULL,
    tinggi VARCHAR(10) NOT NULL,
    status_gizi VARCHAR(50) NOT NULL,
    keterangan TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);