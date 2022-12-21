<?php

$table = 'lansia';
Page::set_title('Tambah Data Pemeriksaan Kesehatan Lansia');
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];
$conn = conn();
$db   = new Database($conn);

if(isset($_GET['posyandu_id']))
{
    unset($fields['posyandu_id']);
}

$penduduk = $db->single('penduduk',['id'=>$_GET['penduduk_id']]);
$sebagai = $penduduk->sebagai == 'Ibu' ? 'Ayah' : 'Ibu';
$pasangan = $db->single('penduduk',['no_kk'=>$penduduk->no_kk,'sebagai'=>$sebagai]);
$kelurahan = $db->single('kelurahan',['id'=>$penduduk->kelurahan_id]);
$penduduk->nama_pasangan = $pasangan ? $pasangan->nama : '';
$penduduk->kelurahan = $kelurahan->nama;

$diff = abs(strtotime('now')-strtotime($penduduk->tanggal_lahir));
$years = floor($diff / (365*60*60*24));
$penduduk->usia = $years;

if(request() == 'POST')
{

    $params = [];

    if(isset($_GET['posyandu_id']))
    {
        $_POST[$table]['posyandu_id'] = $_GET['posyandu_id'];
        $params = $_GET;
    }

    $insert = $db->insert($table,$_POST[$table]);

    set_flash_msg(['success'=>'Data Lansia berhasil ditambahkan']);
    header('location:'.routeTo('kegiatan/posyandu-lansia/index',$params));
}

return compact('table','error_msg','old','fields','penduduk');