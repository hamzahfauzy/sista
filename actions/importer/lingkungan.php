<?php

$conn = conn();
$db   = new Database($conn);

$data = file_get_contents('http://localhost/sispendav/lingkungan.php');
$data = json_decode($data, 1);

foreach($data as $d)
{
    $db->insert('lingkungan',$d);
}

echo "success";