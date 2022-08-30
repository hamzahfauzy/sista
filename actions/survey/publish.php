<?php

$table = 'survey';
$conn = conn();
$db   = new Database($conn);

$db->update($table,['status'=>'publish'],[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>$table.' berhasil di publish']);
header('location:'.routeTo('survey/index'));
die();