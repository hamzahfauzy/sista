<?php

return [
    'dashboard' => 'default/index',
    'timeline'  => 'timeline/index',
    'master'  => [
        'kecamatan'  => 'crud/index?table=kecamatan',
        'Desa / Kelurahan'  => 'crud/index?table=kelurahan',
        'Dusun / Lingkungan' => 'crud/index?table=lingkungan',
        'penduduk'   => 'crud/index?table=penduduk',
        'kategori'   => 'crud/index?table=kategori',
        'indikator'  => 'crud/index?table=indikator',
        'indikator tambahan'  => 'crud/index?table=indikator_tambahan',
        'topik'  => 'crud/index?table=topik',
    ],
    'survey' => [
        'Terverifikasi' => 'survey/index',
        'Mandiri'       => 'survey/mandiri',
    ],
    'posyandu'  => 'crud/index?table=posyandu',
    'kegiatan kesehatan' => [
        'Posyandu' => [
            'Imunisasi Balita' => 'kegiatan/imunisasi/index',
            'Ibu Hamil' => 'kegiatan/ibu-hamil/index',
            'Gizi Balita' => 'kegiatan/ibu-hamil/index',
            'KB'        => 'kegiatan/kb/index',
        ],
        'Posyandu Lansia' => [
            'Pemeriksaan Kesehatan' => 'kegiatan/posyandu-lansia/index',
            'Senam Prolanis' => 'kegiatan/senam-prolanis/index'
        ],
        'Bulan Imunisasi Anak Nasional (BIAN)' => 'kegiatan/bian/index',
        'Bulan Imunisasi Anak Sekolah (BIAS)' => 'kegiatan/bias/index',
        // 'Imunisasi' => 'kegiatan/imunisasi/index',
        // 'Ibu Hamil' => 'kegiatan/ibu-hamil/index',
        // 'KB'        => 'kegiatan/kb/index',
    ],
    'rekapitulasi' => [
        'Indeks Keluarga Sehat' => 'rekapitulasi/index',
        'Cakupan Realisasi dan Masalah Kesehatan' => 'rekapitulasi/realisasi',
        'Kasus Penyakit' => 'rekapitulasi/kasus',
        'Penduduk' => 'rekapitulasi/penduduk',
    ],
    'tindak lanjut permasalahan' => 'feedbacks/index',
    'pengguna'    => [
        'petugas' => 'crud/index?table=petugas',
        'semua pengguna'     => 'users/index',
        'roles'   => 'roles/index'
    ],
    'pengaturan' => 'application/index'
];