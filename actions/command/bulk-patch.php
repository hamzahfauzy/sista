<?php

// check if file exists
$parent_path = '';
if (!in_array(php_sapi_name(),["cli","cgi-fcgi"])) {
    $parent_path = 'public/';
}

if(file_exists($parent_path . 'lock.txt'))
{
    die();
}

file_put_contents($parent_path . 'lock.txt', strtotime('now'));

echo "Patch Start\n";

$conn = conn();
$db   = new Database($conn);

$penduduk = $db->all('penduduk');
foreach($penduduk as $p)
{
    $lingkungan = $db->single('lingkungan',['id'=>$p->lingkungan_id]);
    $new_lingkungan = $db->single('lingkungan',['nama'=>$lingkungan->nama,'kelurahan_id'=>$p->kelurahan_id]);
    $db->update('penduduk',[
        'lingkungan_id' => $new_lingkungan->id
    ],[
        'id' => $p->id
    ]);
}

echo "Patch Finish\n";

unlink($parent_path . 'lock.txt');

die();