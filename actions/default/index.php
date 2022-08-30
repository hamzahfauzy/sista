<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

if(!in_array(get_role($user->id)->name,['administrator','bupati']))
{

}

$kecamatan  = count($db->all('kecamatan'));
$kelurahan  = count($db->all('kelurahan'));
$lingkungan = count($db->all('lingkungan'));
$penduduk = count($db->all('penduduk'));

return compact('kecamatan','kelurahan','lingkungan','penduduk');