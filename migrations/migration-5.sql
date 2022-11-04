CREATE TABLE topik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content LONGTEXT NOT NULL
);

CREATE TABLE feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    kecamatan_id INT DEFAULT NULL,
    kelurahan_id INT DEFAULT NULL,
    lingkungan_id INT DEFAULT NULL,
    topik TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    clause_dest VARCHAR(100) DEFAULT NULL,
    clause_dest_item VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE feedback_receivers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    feedback_id INT NOT NULL,
    user_id INT NOT NULL,
    status INT DEFAULT NULL
);

INSERT INTO topik (content) VALUES("Tingkatkan Profesional Tenaga Kesehatan");
INSERT INTO topik (content) VALUES("Tingkatkan disiplin Tenaga Kesehatan");
INSERT INTO topik (content) VALUES("Perbaiki Data Kesehatan");
INSERT INTO topik (content) VALUES("Lakukan Monitoring");
INSERT INTO topik (content) VALUES("Perbaiki Posyandu");
INSERT INTO topik (content) VALUES("Tingkatkan Kerja Sama");
INSERT INTO topik (content) VALUES("Lakukan Sinergi");
INSERT INTO topik (content) VALUES("Lakukan Koordinasi");
INSERT INTO topik (content) VALUES("Arahkan Kepala Desa");
INSERT INTO topik (content) VALUES("Tekankan Masyarakat");
INSERT INTO topik (content) VALUES("Giatkan Komunikasi");
INSERT INTO topik (content) VALUES("Bangun Kebersamaan");
INSERT INTO topik (content) VALUES("Turun ke Lapangan");
INSERT INTO topik (content) VALUES("Lakukan Perbaikan Data");
INSERT INTO topik (content) VALUES("Perbaiki Data Penduduk");
INSERT INTO topik (content) VALUES("Tingkatkan Akses Internet");
INSERT INTO topik (content) VALUES("Maksimalkan Server");