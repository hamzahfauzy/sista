<?php

$table = 'imunisasi';
Page::set_title('Tambah '.ucwords($table));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];
unset($fields['jenis_imunisasi']);

$conn = conn();
$db   = new Database($conn);

if(isset($_GET['posyandu_id']))
{
    unset($fields['posyandu_id']);
}

if(request() == 'POST')
{
    $jenis_imunisasi = $_POST['jenis_imunisasi'];
    unset($_POST['jenis_imunisasi']);

    $params = [];

    if(isset($_GET['posyandu_id']))
    {
        $_POST[$table]['posyandu_id'] = $_GET['posyandu_id'];
        $params = $_GET;
    }
    
    $_POST[$table]['penduduk_id'] = $_GET['penduduk_id'];

    $insert = $db->insert($table,$_POST[$table]);

    foreach($jenis_imunisasi as $jenis => $imunisasi)
    {
        foreach($imunisasi as $v)
        {
            $db->insert('imunisasi_vaksin',[
                'imunisasi_id' => $insert->id,
                'penduduk_id' => $insert->penduduk_id,
                'nama' => $jenis,
                'jenis' => $v
            ]);
        }
    }

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
    'Polio (IPV)' => ['24 Jam',2,3,4],
    'BCG' => [1],
    'Campak Rubella' => [9,18,60],
    'DPT-HB-HiB' => [2,3,4,18],
];

$vaksins = $db->all('imunisasi_vaksin', ['penduduk_id'=>$_GET['penduduk_id']]);
$vaksin_exists = [];
foreach($vaksins as $jv)
{
    $vaksin_exists[$jv->nama][$jv->jenis] = true;
}

// $available = [];
// foreach($jenis_imunisasi as $jenis => $usia)
// {
//     if(in_array($penduduk->usia,$usia))
//     {
//         $available[] = $jenis;
//     }
// }

// $fields['jenis_imunisasi']['label'] = 'Jenis Imunisasi';
// $fields['jenis_imunisasi']['type'] = 'options:'.implode('|',$available);

return compact('table','error_msg','old','fields','penduduk','jenis_imunisasi','vaksin_exists');