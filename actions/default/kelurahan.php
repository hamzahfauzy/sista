<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

if(get_role($user->id)->name == 'pembina kelurahan')
{
    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kelurahan_id = $petugas->kelurahan_id??($_GET['kelurahan_id']??0);
}
else
{
    die();
}

$lingkungan = $db->exists('lingkungan',['kelurahan_id'=>$kelurahan_id]);
$penduduk = $db->exists('penduduk',['kelurahan_id'=>$kelurahan_id]);
$db->query = "SELECT no_kk FROM penduduk WHERE kelurahan_id=$kelurahan_id AND (no_kk IS NOT NULL or no_kk != '') GROUP BY no_kk";
$jumlah_kk = $db->exec('exists');

return compact('lingkungan','penduduk','jumlah_kk');