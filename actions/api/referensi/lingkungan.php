<?php

$conn = conn();
$db   = new Database($conn);

$kelurahan_id = $_GET['kelurahan_id'];
$lingkungan = $db->all('lingkungan',['kelurahan_id'=>$kelurahan_id]);

echo json_encode([
    'status' => 'success',
    'data' => $lingkungan,
    'message' => 'list data lingkungan',
]);
die();