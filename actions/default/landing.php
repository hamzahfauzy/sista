<?php

$conn = conn();
$db   = new Database($conn);
$nik  = isset($_GET['nik']) ? $_GET['nik'] : '';
$data = [];

if($nik)
{
    $data = $db->all('survey',[
        'status' => 'publish',
        'nilai'  => ['LIKE','%'.$nik.'%']
    ]);
}

return compact('nik','data');