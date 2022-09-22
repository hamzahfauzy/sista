<?php

$table = 'survey';
$conn = conn();
$db   = new Database($conn);

$db->update($table,['status'=>'publish'],[
    'id' => $_GET['id']
]);

echo json_encode(['status'=>'success']);
die();