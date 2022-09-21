<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

$periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

if(!in_array(get_role($user->id)->name,['administrator','bupati']))
{
    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kecamatan_id = $petugas->kecamatan_id;
    if(!empty($petugas->kelurahan_id))
    {
        header('location:'.routeTo('default/kelurahan',[
            'kelurahan_id' => $petugas->kelurahan_id,
            'tahun' => $periode,
        ]));
    }
    header('location:'.routeTo('default/kecamatan',[
        'kecamatan_id' => $kecamatan_id,
        'tahun' => $periode,
    ]));
    die();
}