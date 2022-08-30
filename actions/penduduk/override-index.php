<?php

$user = auth()->user;

if(!in_array(get_role($user->id)->name,['administrator','bupati']))
{
    $petugas = $db->single('petugas',['user_id' => $user->id]);
    $data = $db->all('penduduk',['kecamatan_id' => $petugas->kecamatan_id]);
}

return $data;