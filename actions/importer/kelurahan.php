<?php

$conn = conn();
$db   = new Database($conn);

$data = file_get_contents('http://localhost/sispendav/kelurahan.php');
$data = json_decode($data, 1);

foreach($data as $d)
{
    $d['kecamatan_id'] = 1;
    $db->insert('kelurahan',$d);
}

echo "success";