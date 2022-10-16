<?php

// $user = auth()->user;

// if(!in_array(get_role($user->id)->name,['administrator','bupati']))
// {

//     $petugas = $db->single('petugas',['user_id'=>$user->id]);
//     $kecamatan_id = $petugas->kecamatan_id;
//     if(!empty($petugas->kelurahan_id))
//     {
//         $data = $db->all('penduduk',['kelurahan_id' => $petugas->kelurahan_id]);
//     }

//     else if(!empty($kecamatan_id))
//     {
//         $data = $db->all('penduduk',['kecamatan_id' => $kecamatan_id]);
//     }

//     else
//     {
//         $data = [];
//     }
// }

// return $data;

return [];