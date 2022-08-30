<?php

Page::set_title('Detail Survey');

$conn = conn();
$db   = new Database($conn);

$data = $db->single('survey',['id' => $_GET['id']]);
$data->nilai = json_decode($data->nilai);
$data->kategori = json_decode($data->kategori);

return compact('data');