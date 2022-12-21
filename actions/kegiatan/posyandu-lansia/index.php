<?php

$table = 'lansia';
Page::set_title('Pemeriksaan Kesehatan Lansia');
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$fields = config('fields')[$table];
$actions = [];

$params = [];

unset($fields['nama_pasangan']);
unset($fields['usia']);
unset($fields['jumlah_anak']);
unset($fields['status']);
unset($fields['jenis']);
unset($fields['keterangan']);

if(isset($_GET['posyandu_id']))
{
    $params['posyandu_id'] = $_GET['posyandu_id'];
}

$data = $db->all($table, $params, [
    'id' => 'DESC'
]);

return [
    'datas' => $data,
    'table' => $table,
    'success_msg' => $success_msg,
    'fields' => $fields,
    'actions' => $actions
];