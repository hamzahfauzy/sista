<?php

return [
    'kecamatan'    => [
        'nama'
    ],
    'kelurahan'    => [
        'kecamatan_id' => [
            'label' => 'Kecamatan',
            'type'  => 'options-obj:kecamatan,id,nama'
        ],
        'nama',
    ],
    'lingkungan'    => [
        'kelurahan_id' => [
            'label' => 'Desa/Kelurahan',
            'type'  => 'options-obj:kelurahan,id,nama'
        ],
        'nama',
    ],
    'penduduk' => [
        'no_kk' => [
            'label' => 'No KK',
            'type'  => 'text'
        ],
        'sebagai' => [
            'label' => 'Sebagai',
            'type'  => 'options:Ayah|Ibu|Anak'
        ],
        'NIK' => [
            'label' => 'NIK',
            'type'  => 'text'
        ],
        'nama' => [
            'label' => 'Nama',
            'type'  => 'text'
        ],
        'alamat' => [
            'label' => 'Alamat',
            'type'  => 'text'
        ],
        'tanggal_lahir' => [
            'label' => 'Tanggal Lahir',
            'type'  => 'date'
        ],
        'kecamatan_id' => [
            'label' => 'Kecamatan',
            'type'  => 'options-obj:kecamatan,id,nama'
        ],
        'kelurahan_id' => [
            'label' => 'Desa/Kelurahan',
            'type'  => 'options-obj:kelurahan,id,nama'
        ],
        'lingkungan_id' => [
            'label' => 'Dusun/Lingkungan',
            'type'  => 'options-obj:lingkungan,id,nama'
        ]
    ],
    'kategori' => [
        'nama',
        'nilai_awal' => [
            'label' => 'Nilai Awal',
            'type'  => 'number'
        ],
        'nilai_akhir' => [
            'label' => 'Nilai Akhir',
            'type'  => 'number'
        ],
        'warna' => [
            'label' => 'Warna',
            'type'  => 'color'
        ],
    ],
    'indikator' => [
        'nama',
        'no_urut' => [
            'label' => 'No Urut',
            'type'  => 'number'
        ],
        'pengaturan' => [
            'label' => 'Pengaturan',
            'type'  => 'checkbox:ayah|ibu|anak > 5 tahun|anak balita|anak bayi'
        ],
        'logika' => [
            'label' => 'Logika Skoring',
            'type'  => 'options:or|and'
        ],
        'jawaban' => [
            'label' => 'Jawaban Skoring',
            'type'  => 'options:N|Y|T'
        ],
    ],
    'petugas' => [
        'kecamatan_id' => [
            'label' => 'Kecamatan',
            'type'  => 'options-obj:kecamatan,id,nama'
        ],
        'kelurahan_id' => [
            'label' => 'Desa / Kelurahan',
            'type'  => 'options-obj:kelurahan,id,nama'
        ],
        'NIK',
        'nama',
        'alamat',
        'jenis_kelamin' => [
            'label' => 'Jenis Kelamin',
            'type'  => 'options:Laki-laki|Perempuan'
        ],
        'no_hp',
        'email'
    ],
    'survey' => [
        'no_kk' => [
            'label' => 'ID Keluarga',
            'type'  => 'text'
        ],
        'tanggal' => [
            'label' => 'Tanggal',
            'type'  => 'date'
        ],
    ],
    'topik' => [
        'content' => [
            'label' => 'Topik',
            'type' => 'textarea',
        ]
    ],
    'feedbacks' => [
        'clause_dest' => [
            'label' => 'Kepada :',
            'type'  => 'options:- Pilih -|Semua Pembina|Pembina Kabupaten|Pembina Kecamatan'
        ],
        'clause_dest_item' => [
            'label' => 'Untuk :',
            'type'  => 'options:-'
        ],
        // 'kecamatan_id' => [
        //     'label' => 'Kecamatan',
        //     'type'  => 'options-obj:kecamatan,id,nama'
        // ],
        // 'kelurahan_id' => [
        //     'label' => 'Desa/Kelurahan',
        //     'type'  => 'options-obj:kelurahan,id,nama'
        // ],
        // 'lingkungan_id' => [
        //     'label' => 'Dusun/Lingkungan',
        //     'type'  => 'options-obj:lingkungan,id,nama'
        // ],
        'topik' => [
            'label' => 'Topik Permasalahan :',
            'type'  => 'options-obj:topik,content,content'
        ],
        'content' => [
            'label' => 'Isi Pesan Tindak Lanjut Permasalahan :',
            'type'  => 'textarea'
        ],
        'created_at' => [
            'label' => 'Tanggal',
            'type'  => 'date'
        ]
    ],
    'indikator_tambahan' => [
        'deskripsi' => [
            'label' => 'Deskripsi',
            'type'  => 'textarea'
        ],
        'pilihan' => [
            'label' => 'Pilihan',
            'type'  => 'textarea'
        ]
    ],
    'posyandu' => [
        'nama' => [
            'label' => 'Nama Posyandu',
            'type'  => 'text'
        ],
        'kecamatan_id' => [
            'label' => 'Kecamatan',
            'type'  => 'options-obj:kecamatan,id,nama'
        ],
        'kelurahan_id' => [
            'label' => 'Desa / Kelurahan',
            'type'  => 'options-obj:kelurahan,id,nama'
        ],
        'lingkungan_id' => [
            'label' => 'Wilayah Cakupan Posyandu',
            'type'  => 'options-obj:lingkungan,id,nama'
        ],
        'alamat',
        'strata' => [
            'label' => 'Strata',
            'type'  => 'options:Pratama|Madya|Purnama|Mandiri'
        ],
        'file' => [
            'label' => 'Gambar Posyandu',
            'type'  => 'file'
        ]
    ],
    'imunisasi' => [
        'posyandu_id' => [
            'label' => 'Posyandu',
            'type'  => 'options-obj:posyandu,id,nama'
        ],
        'nama',
        'usia' => [
            'label' => 'Usia (Bulan)',
            'type'  => 'number'
        ],
        'bulan' => [
            'label' => 'Bulan Pelaksanaan',
            'type'  => 'options:Pilih|Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember'
        ],
        'berat' => [
            'label' => 'Berat (Kg)',
            'type'  => 'number'
        ],
        'tinggi' => [
            'label' => 'Tinggi (Cm)',
            'type'  => 'number'
        ],
        'keluhan' => [
            'label' => 'Keluhan',
            'type'  => 'options:Tidak ada|Tidak mau makan|Demam|Diare|Flu dan Batuk|ISPA|Sembelit|Kolik|Gigitan Serangga|Ruam Popok'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'type'  => 'text'
        ],
    ],
    'ibu_hamil' => [
        'NIK',
        'nama',
        'nama_suami',
        'alamat',
        'kelurahan',
        'status_peserta' => [
            'label' => 'Status Peserta',
            'type'  => 'options:Aktif|Tidak Aktif|Tidak Ikut'
        ],
        'hpht' => [
            'label' => 'HPHT (Awal Kehamilan)',
            'type'  => 'date'
        ],
    ],
    'kegiatan_kb' => [
        'posyandu_id' => [
            'label' => 'Posyandu',
            'type'  => 'options-obj:posyandu,id,nama'
        ],
        'NIK' => [
            'label' => 'NIK',
            'type'  => 'text'
        ],
        'nama' => [
            'label' => 'Nama Akseptor',
            'type'  => 'text'
        ],
        'nama_pasangan' => [
            'label' => 'Nama Suami/Istri',
            'type'  => 'text'
        ],
        'usia' => [
            'label' => 'Usia Akseptor',
            'type'  => 'number'
        ],
        'alamat' => [
            'label' => 'Alamat',
            'type'  => 'text'
        ],
        'kelurahan' => [
            'label' => 'Desa / Kelurahan',
            'type'  => 'text'
        ],
        'jumlah_anak' => [
            'label' => 'Jumlah Anak',
            'type'  => 'options:1 Anak|2 Anak|3 Anak|4 Anak|5 Anak|6 Anak'
        ],
        'status' => [
            'label' => 'Status',
            'type'  => 'options:Akseptor Baru|Pernaj menjadi Akseptor|Berhenti menjadi Akseptor'
        ],
        'jenis' => [
            'label' => 'Jenis Alat Kontrasepsi',
            'type'  => 'options:IUD|Implan|Suntik KB|PIL KB|Kondom|Spermisida|Diafragma'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'type'  => 'text'
        ]
    ],
    'pemantauan_gizi' => [
        'posyandu_id' => [
            'label' => 'Posyandu',
            'type'  => 'options-obj:posyandu,id,nama'
        ],
        'nama',
        'nama_orangtua',
        'jenis_kelamin' => [
            'label' => 'Jenis Kelamin',
            'type'  => 'options:Laki-laki|Perempuan'
        ],
        'tanggal_lahir',
        'usia' => [
            'label' => 'Usia (Bulan)',
            'type'  => 'number'
        ],
        'alamat',
        'tanggal_pemeriksaan' => [
            'label' => 'Tanggal Pemeriksaan',
            'type'  => 'date'
        ],
        'status_pemantauan' => [
            'label' => 'Status Mengikuti Pemantauan',
            'type'  => 'options:Aktif Pemeriksaan|Tidak Aktif Pemeriksaan|Tidak Pernah Pemeriksaan'
        ],
        'berat' => [
            'label' => 'Berat (Kg)',
            'type'  => 'number'
        ],
        'tinggi' => [
            'label' => 'Tinggi (Cm)',
            'type'  => 'number'
        ],
        'status_gizi' => [
            'label' => 'Status Gizi',
            'type'  => 'options:Gizi Sehat|Gizi Cukup|Gizi Kurang|Gizi Buruk'
        ],
        'keterangan' => [
            'label' => 'Keterangan',
            'type'  => 'options:Balita Sehat|Balita Tidak Sehat|Balita Sakit|Perlu Mendapat Perhatian'
        ],
    ]
];