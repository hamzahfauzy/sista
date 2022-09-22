<?php

$table = 'survey';
$conn = conn();
$db   = new Database($conn);

$db->delete($table,[
    'id' => $_GET['id']
]);

echo json_encode(['status'=>'success']);
die();