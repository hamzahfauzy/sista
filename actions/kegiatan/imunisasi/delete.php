<?php

$table = 'imunisasi';
$conn = conn();
$db   = new Database($conn);

$data = $db->single($table, ['id'=>$_GET['id']]);

$db->delete($table,[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>$table.' berhasil dihapus']);
header('location:'.routeTo('kegiatan/imunisasi/index',['posyandu_id'=>$data->posyandu_id]));
die();