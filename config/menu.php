<?php

return [
    'dashboard' => 'default/index',
    'master'  => [
        'kecamatan'  => 'crud/index?table=kecamatan',
        'Desa / Kelurahan'  => 'crud/index?table=kelurahan',
        'Dusun / Lingkungan' => 'crud/index?table=lingkungan',
        'penduduk'   => 'crud/index?table=penduduk',
        'kategori'   => 'crud/index?table=kategori',
        'indikator'  => 'crud/index?table=indikator',
    ],
    'survey' => 'survey/index',
    'rekapitulasi' => [
        'Indeks Keluarga Sehat' => 'rekapitulasi/index',
        'Cakupan Realisasi dan Masalah Kesehatan' => 'rekapitulasi/realisasi',
        'Kasus Penyakit' => 'rekapitulasi/kasus',
        'Penduduk' => 'rekapitulasi/penduduk',
    ],
    'pengguna'    => [
        'petugas' => 'crud/index?table=petugas',
        'semua pengguna'     => 'users/index',
        'roles'   => 'roles/index'
    ],
    'pengaturan' => 'application/index'
];