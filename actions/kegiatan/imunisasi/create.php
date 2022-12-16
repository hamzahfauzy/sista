<?php

$table = 'imunisasi';
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
    
    if(isset($_GET['penduduk_id']))
    {
        $_POST[$table]['penduduk_id'] = $_GET['penduduk_id'];
    }

    $insert = $db->insert($table,$_POST[$table]);

    set_flash_msg(['success'=>$table.' berhasil ditambahkan']);
    header('location:'.routeTo('kegiatan/imunisasi/index',$params));
}

$penduduk = $db->single('penduduk',['id'=>$_GET['penduduk_id']]);
$diff = abs(strtotime('now')-strtotime($penduduk->tanggal_lahir));
$years = floor($diff / (365*60*60*24));
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
$penduduk->usia = $months;

$jenis_imunisasi = [
    'Hepatitis B (HB-0)' => [2,3,4,18],
    'Polio (IPV)' => [24,2,3,4],
    'BCG' => [1],
    'Campak Rubella' => [9,18,60],
    'DPT-HB-HiB' => [2,3,4,18],
];

$available = [];
foreach($jenis_imunisasi as $jenis => $usia)
{
    if(in_array($penduduk->usia,$usia))
    {
        $available[] = $jenis;
    }
}

$fields['jenis_imunisasi']['label'] = 'Jenis Imunisasi';
$fields['jenis_imunisasi']['type'] = 'options:'.implode('|',$available);

return compact('table','error_msg','old','fields','penduduk');