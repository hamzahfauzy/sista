<?php

$user = auth()->user;

if(!in_array(get_role($user->id)->name,['administrator','pembina kabupaten','bupati']))
{

    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kecamatan_id = $petugas->kecamatan_id;
    if(!empty($petugas->kelurahan_id))
    {
        $data = $db->all('lingkungan',['kelurahan_id' => $petugas->kelurahan_id]);
    }

    else if(!empty($kecamatan_id))
    {
        $db->query = "SELECT id FROM kelurahan WHERE kecamatan_id = $kecamatan_id";
        $all_kelurahan = $db->exec('all');
        $all_kelurahan = array_map(function($d){
            return $d->id;
        }, $all_kelurahan);

        $kelurahan_id = "(0)";
        if($all_kelurahan)
        {
            $kelurahan_id = "(".implode(',',$all_kelurahan).")";
        }

        $data = $db->all('lingkungan',['kelurahan_id' => ['IN',$kelurahan_id]]);
    }

    else
    {
        $data = [];
    }
}

return $data;