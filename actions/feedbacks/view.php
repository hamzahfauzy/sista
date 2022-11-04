<?php

$table = 'feedbacks';
$conn = conn();
$db   = new Database($conn);

$data = $db->single($table,[
    'id' => $_GET['id']
]);

return [
    'data' => $data,
];