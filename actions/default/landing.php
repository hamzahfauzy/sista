<?php

$conn = conn();
$db   = new Database($conn);
$nik  = isset($_GET['nik']) ? $_GET['nik'] : '';
$data = [];
$success_msg = get_flash_msg('success');

if($nik)
{
    $data = $db->all('survey',[
        'status' => 'publish',
        'nilai'  => ['LIKE','%'.$nik.'%']
    ]);
}

return compact('nik','data','success_msg');