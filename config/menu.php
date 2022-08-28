<?php

return [
    'dashboard' => 'default/index',
    'master'  => [
        'kecamatan'  => 'crud/index?table=kecamatan',
        'kelurahan'  => 'crud/index?table=kelurahan',
        'lingkungan' => 'crud/index?table=lingkungan',
        'penduduk'   => 'crud/index?table=penduduk',
        'kategori'   => 'crud/index?table=kategori',
        'indikator'  => 'crud/index?table=indikator',
    ],
    'survey' => 'survey/index',
    'pengguna'    => [
        'petugas' => 'crud/index?table=petugas',
        'semua pengguna'     => 'users/index',
        'roles'   => 'roles/index'
    ],
    'pengaturan' => 'application/index'
];