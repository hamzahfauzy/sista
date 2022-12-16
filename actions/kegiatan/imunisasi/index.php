<?php

$table = 'imunisasi';
Page::set_title(ucwords($table));
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$fields = config('fields')[$table];
$actions = [];

$params = [];

if(isset($_GET['posyandu_id']))
{
    $params['posyandu_id'] = $_GET['posyandu_id'];
}

$data = $db->all($table, $params, [
    'id' => 'DESC'
]);

$data = array_map(function($d) use ($db){
    $jenis_vaksin = $db->all('imunisasi_vaksin', ['penduduk_id'=>$d->penduduk_id]);
    $vaksin_exists = [];
    foreach($jenis_vaksin as $jv)
    {
        $vaksin_exists[$jv->nama][] = $jv->jenis;
    }
    $d->jenis_imunisasi = $vaksin_exists;
    return $d;
}, $data);

// $fields['jenis_imunisasi'] = [
//     'label' => 'Jenis Imunisasi',
//     'type' => 'text'
// ];

return [
    'datas' => $data,
    'table' => $table,
    'success_msg' => $success_msg,
    'fields' => $fields,
    'actions' => $actions
];