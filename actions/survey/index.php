<?php

$table = 'survey';
Page::set_title(ucwords($table));
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$fields = config('fields')[$table];
$actions = [];

$user = auth()->user;

$data = $db->all($table);
if(!in_array(get_role($user->id)->name,['administrator','bupati']))
{
    $petugas = $db->single('petugas',['user_id' => $user->id]);
    $db->query = "SELECT no_kk FROM penduduk WHERE kecamatan_id = $petugas->kecamatan_id GROUP BY no_kk";
    $all_kk = $db->exec('all');
    $all_kk = array_map(function($a){
        return $a->no_kk;
    }, $all_kk);
    if($all_kk)
    {
        $kk = "('".implode("','",$all_kk)."')";
        $data = $db->all($table,['no_kk' => ['IN',$kk]]);
    }
    else
    {
        $data = [];
    }
}

return [
    'datas' => $data,
    'table' => $table,
    'success_msg' => $success_msg,
    'fields' => $fields,
    'actions' => $actions
];