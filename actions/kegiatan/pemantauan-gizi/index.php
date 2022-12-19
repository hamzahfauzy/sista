<?php

$table = 'pemantauan_gizi';
Page::set_title('Pemantauan Gizi');
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

return [
    'datas' => $data,
    'table' => $table,
    'success_msg' => $success_msg,
    'fields' => $fields,
    'actions' => $actions
];