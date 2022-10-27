<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

$periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

if(!in_array(get_role($user->id)->name,['administrator','pembina kabupaten','bupati']))
{
    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kecamatan_id = $petugas->kecamatan_id;
    if(!empty($petugas->kelurahan_id))
    {
        header('location:'.routeTo('default/kelurahan'));
        die();
    }
    header('location:'.routeTo('default/kecamatan'));
    die();
}

$kecamatan = $db->exists('kecamatan');
$kelurahan = $db->exists('kelurahan');
$lingkungan = $db->exists('lingkungan');
$penduduk = $db->exists('penduduk');
$db->query = "SELECT no_kk FROM penduduk WHERE no_kk IS NOT NULL or no_kk != '' GROUP BY no_kk";
$jumlah_kk = $db->exec('exists');
$iks = (new Iks)->all($periode);
$db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks AND nilai_akhir >= $iks";
$kategori_iks = $db->exec('single');

return compact('kecamatan','kelurahan','lingkungan','penduduk','jumlah_kk','iks','periode','kategori_iks');