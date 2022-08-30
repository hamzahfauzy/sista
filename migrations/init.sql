CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE role_routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    route_path VARCHAR(100) NOT NULL,
    CONSTRAINT fk_role_routes_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE user_roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    CONSTRAINT fk_user_roles_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_roles_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE application (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

CREATE TABLE migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(100) NOT NULL,
    execute_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kecamatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);

CREATE TABLE kelurahan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kecamatan_id INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    CONSTRAINT fk_kelurahan_kecamatan_id FOREIGN KEY (kecamatan_id) REFERENCES kecamatan(id) ON DELETE CASCADE
);

CREATE TABLE lingkungan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kelurahan_id INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    CONSTRAINT fk_lingkungan_kelurahan_id FOREIGN KEY (kelurahan_id) REFERENCES kelurahan(id) ON DELETE CASCADE
);

CREATE TABLE penduduk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_kk VARCHAR(100) NOT NULL,
    NIK VARCHAR(100) NOT NULL,
    sebagai VARCHAR(100) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    tanggal_lahir DATE NOT NULL,
    kecamatan_id INT NOT NULL,
    kelurahan_id INT NOT NULL,
    lingkungan_id INT NOT NULL,
    hapus INT DEFAULT NULL,
    CONSTRAINT fk_penduduk_kecamatan_id FOREIGN KEY (kecamatan_id) REFERENCES kecamatan(id) ON DELETE CASCADE,
    CONSTRAINT fk_penduduk_kelurahan_id FOREIGN KEY (kelurahan_id) REFERENCES kelurahan(id) ON DELETE CASCADE,
    CONSTRAINT fk_penduduk_lingkungan_id FOREIGN KEY (lingkungan_id) REFERENCES lingkungan(id) ON DELETE CASCADE
);

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nilai_awal DOUBLE NOT NULL,
    nilai_akhir DOUBLE NOT NULL,
    warna VARCHAR(100) NOT NULL
);

CREATE TABLE indikator (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    no_urut INT NOT NULL,
    pengaturan LONGTEXT NOT NULL,
    logika VARCHAR(20) NOT NULL DEFAULT "or",
    jawaban VARCHAR(20) NOT NULL DEFAULT "Y"
);

CREATE TABLE survey (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_kk VARCHAR(100) NOT NULL,
    nilai LONGTEXT DEFAULT NULL,
    status VARCHAR(100) NOT NULL,
    kategori TEXT NOT NULL,
    tanggal DATE NOT NULL,
    berkas VARCHAR(100) NOT NULL
);

CREATE TABLE petugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    kecamatan_id INT NOT NULL,
    NIK VARCHAR(100) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT NOT NULL,
    jenis_kelamin VARCHAR(45) NOT NULL,
    no_hp VARCHAR(45) NOT NULL
);