<?php

$table = 'ibu_hamil';
Page::set_title('Tambah Data Ibu Hamil');
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
$penduduk->nama_suami = $pasangan ? $pasangan->nama : '';
$penduduk->kelurahan = $kelurahan->nama;
$penduduk->usia_kandungan = 0;

if(request() == 'POST')
{

    $params = [];

    if(isset($_GET['posyandu_id']))
    {
        $_POST[$table]['posyandu_id'] = $_GET['posyandu_id'];
        $params = $_GET;
    }

    $insert = $db->insert($table,$_POST[$table]);

    set_flash_msg(['success'=>$table.' berhasil ditambahkan']);
    header('location:'.routeTo('kegiatan/ibu-hamil/index', $params));
}

return compact('table','error_msg','old','fields','penduduk');