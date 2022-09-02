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
            'label' => 'Kelurahan',
            'type'  => 'options-obj:kelurahan,id,nama'
        ],
        'NIK',
        'nama',
        'alamat',
        'jenis_kelamin' => [
            'label' => 'Jenis Kelamin',
            'type'  => 'options:Laki-laki|Perempuan'
        ],
        'no_hp'
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
    ]
];