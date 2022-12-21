<?php

$table = 'bian';
Page::set_title('Tambah '.ucwords($table));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];

$conn = conn();
$db   = new Database($conn);

if(isset($_GET['posyandu_id']))
{
    unset($fields['posyandu_id']);
}

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
    header('location:'.routeTo('kegiatan/bian/index',$params));
}

$penduduk = $db->single('penduduk',['id'=>$_GET['penduduk_id']]);
$diff = abs(strtotime('now')-strtotime($penduduk->tanggal_lahir));
$years = floor($diff / (365*60*60*24));
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
$penduduk->usia = $years;
$penduduk->nama_orangtua = $db->single('penduduk',['no_kk'=>$penduduk->no_kk,'sebagai'=>['IN',"('Ayah','Ibu')"]])->nama;
$penduduk->kelurahan = $db->single('kelurahan',['id'=>$penduduk->kelurahan_id])->nama;

return compact('table','error_msg','old','fields','penduduk');