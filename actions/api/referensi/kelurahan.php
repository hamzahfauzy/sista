<?php

$conn = conn();
$db   = new Database($conn);

$kecamatan_id = $_GET['kecamatan_id'];
$kelurahan = $db->all('kelurahan',['kecamatan_id'=>$kecamatan_id]);

echo json_encode([
    'status' => 'success',
    'data' => $kelurahan,
    'message' => 'list data kelurahan',
]);
die();