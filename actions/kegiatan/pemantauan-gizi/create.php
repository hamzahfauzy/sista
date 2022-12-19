<?php

$table = 'pemantauan_gizi';
Page::set_title('Tambah Pemantauan Gizi');
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
    
    $_POST[$table]['penduduk_id'] = $_GET['penduduk_id'];

    $insert = $db->insert($table,$_POST[$table]);

    set_flash_msg(['success'=>$table.' berhasil ditambahkan']);
    header('location:'.routeTo('kegiatan/pemantauan-gizi/index',$params));
}

$penduduk = $db->single('penduduk',['id'=>$_GET['penduduk_id']]);
$diff = abs(strtotime('now')-strtotime($penduduk->tanggal_lahir));
$years = floor($diff / (365*60*60*24));
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
$penduduk->usia = ($years*12) + $months;

return compact('table','error_msg','old','fields','penduduk');