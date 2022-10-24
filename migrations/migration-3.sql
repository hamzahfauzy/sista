CREATE TABLE iks_penduduk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun INT NOT NULL,
    kecamatan_id INT NOT NULL,
    kelurahan_id INT NOT NULL,
    lingkungan_id INT NOT NULL,
    no_kk VARCHAR(45) NOT NULL,
    skor VARCHAR(45) NOT NULL,
    status VARCHAR(45) DEFAULT "draft"
);

CREATE TABLE iks_indikator (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun INT NOT NULL,
    indikator_id INT NOT NULL,
    kecamatan_id INT NOT NULL,
    kelurahan_id INT NOT NULL,
    lingkungan_id INT NOT NULL,
    no_kk VARCHAR(45) NOT NULL,
    skor VARCHAR(45) NOT NULL,
    status VARCHAR(45) DEFAULT "draft"
);

INSERT INTO roles(name) VALUES('penduduk');
INSERT INTO role_routes(role_id,route_path) VALUES(7,'default/riwayat');
INSERT INTO role_routes(role_id,route_path) VALUES(7,'default/download');