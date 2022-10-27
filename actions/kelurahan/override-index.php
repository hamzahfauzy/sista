<?php

$user = auth()->user;

if(!in_array(get_role($user->id)->name,['administrator','pembina kabupaten','bupati']))
{

    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kecamatan_id = $petugas->kecamatan_id;
    if(!empty($petugas->kelurahan_id))
    {
        $data = [];
    }

    else if(!empty($kecamatan_id))
    {
        $data = $db->all('kelurahan',['kecamatan_id' => $kecamatan_id]);
    }

    else
    {
        $data = [];
    }
}

return $data;

// return [];