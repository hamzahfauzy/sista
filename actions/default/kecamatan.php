<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

if(!in_array(get_role($user->id)->name,['administrator','pembina kabupaten','bupati']))
{
    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kecamatan_id = $petugas->kecamatan_id;
    if(!empty($petugas->kelurahan_id))
    {
        header('location:'.routeTo('default/kelurahan'));
        die();
    }

    $kecamatan_id = $kecamatan_id ? $kecamatan_id : (isset($_GET['kecamatan_id']) ? $_GET['kecamatan_id'] : 0);
}

$kecamatan = $db->exists('kecamatan',['id' => $kecamatan_id]);
$kelurahan = $db->exists('kelurahan',['kecamatan_id' => $kecamatan_id]);

$db->query = "SELECT id FROM kelurahan WHERE kecamatan_id = $kecamatan_id";
$all_kelurahan = $db->exec('all');
$all_kelurahan = array_map(function($d){
    return $d->id;
}, $all_kelurahan);

$kelurahan_id = "(0)";
if($all_kelurahan)
{
    $kelurahan_id = "(".implode(',',$all_kelurahan).")";
}

$lingkungan = $db->exists('lingkungan',['kelurahan_id' => ['IN',$kelurahan_id]]);
$penduduk = $db->exists('penduduk',['kecamatan_id'=>$kecamatan_id]);
$db->query = "SELECT no_kk FROM penduduk WHERE kecamatan_id=$kecamatan_id AND (no_kk IS NOT NULL or no_kk != '') GROUP BY no_kk";
$jumlah_kk = $db->exec('exists');

return compact('kelurahan','lingkungan','penduduk','jumlah_kk');