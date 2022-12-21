<?php

$table = 'bias';
$conn = conn();
$db   = new Database($conn);


$db->delete($table,[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>$table.' berhasil dihapus']);
header('location:'.routeTo('kegiatan/bias/index'));
die();