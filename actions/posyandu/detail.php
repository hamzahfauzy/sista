<?php

$conn = conn();
$db   = new Database($conn);
$data = $db->single('posyandu',[
    'id' => $_GET['id']
]);

Page::set_title('Detail Posyandu | '.$data->nama);
$success_msg = get_flash_msg('success');

return compact('data','success_msg');