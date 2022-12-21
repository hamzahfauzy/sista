CREATE TABLE lansia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posyandu_id INT NOT NULL,
    NIK VARCHAR(50) NULL,
    nama VARCHAR(100) NOT NULL,
    nama_pasangan VARCHAR(100) NOT NULL,
    usia VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    kelurahan VARCHAR(50) NOT NULL,
    jumlah_anak INT NOT NULL,
    lokasi_cek VARCHAR(50) NOT NULL,
    status_cek VARCHAR(50) NOT NULL,
    tekanan_darah VARCHAR(10) NOT NULL,
    berat_badan VARCHAR(10) NOT NULL,
    gula_darah VARCHAR(10) NOT NULL,
    kolesterol VARCHAR(10) NOT NULL,
    lingkar_perut VARCHAR(50) NOT NULL,
    status_kesehatan VARCHAR(50) NOT NULL,
    riwayat_penyakit VARCHAR(100) NOT NULL,
    keterangan VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posyandu_id INT NOT NULL,
    NIK VARCHAR(50) NULL,
    nama VARCHAR(100) NOT NULL,
    tanggal_lahir VARCHAR(100) NOT NULL,
    usia VARCHAR(50) NOT NULL,
    nama_orangtua VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    kelurahan VARCHAR(50) NOT NULL,
    sekolah VARCHAR(100) NOT NULL,
    jenis_imunisasi VARCHAR(50) NOT NULL,
    tanggal_pemeriksaan DATE NOT NULL,
    alasan VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    posyandu_id INT NOT NULL,
    NIK VARCHAR(50) NULL,
    nama VARCHAR(100) NOT NULL,
    tanggal_lahir VARCHAR(100) NOT NULL,
    usia VARCHAR(50) NOT NULL,
    nama_orangtua VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    kelurahan VARCHAR(50) NOT NULL,
    sekolah VARCHAR(100) NOT NULL,
    jenis_imunisasi VARCHAR(50) NOT NULL,
    tanggal_pemeriksaan DATE NOT NULL,
    alasan VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);