<?php

$table = 'feedbacks';
Page::set_title('Tambah '.ucwords($table));
$conn = conn();
$db   = new Database($conn);
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];

unset($fields['created_at']);

$data = json_decode(json_encode([
    'clause_dest' => '',
    'clause_dest_item' => '',
    'kecamatan_id' => '',
    'kelurahan_id' => '',
    'lingkungan_id' => '',
    'content' => '',
    'topik' => '',
]));

unset($fields['kecamatan_id']);
unset($fields['kelurahan_id']);
unset($fields['lingkungan_id']);

$user = auth()->user;
if(get_role($user->id)->name == 'pembina kecamatan')
{
    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    unset($fields['clause_dest']);
    $fields['clause_dest_item']['type'] = 'options-obj:kelurahan,id,nama,kecamatan_id,'.$petugas->kecamatan_id;
}


if(request() == 'POST')
{
    $_POST[$table]['clause_dest'] = isset($_POST[$table]['clause_dest']) ? strtolower($_POST[$table]['clause_dest']) : 'pembina kelurahan';
    $_POST[$table]['user_id'] = auth()->user->id;
    
    $clause_dest_item = implode(",",$_POST[$table]['clause_dest_item']);
    $role  = $db->single('roles',['name' => $_POST[$table]['clause_dest']]);
    $items = [];

    if($_POST[$table]['clause_dest_item'][0] != 'Semua')
    {
        $query = "";
        if($_POST[$table]['clause_dest'] == 'pembina kelurahan')
        {
            $query = "SELECT * FROM petugas WHERE kelurahan_id IN ($clause_dest_item) AND user_id IN (SELECT user_id FROM user_roles WHERE role_id=$role->id)";
        }
        else
        {
            $query = "SELECT * FROM petugas WHERE user_id IN ($clause_dest_item)";
        }
    }
    else
    {
        $query = "";
        if($_POST[$table]['clause_dest'] == 'pembina kelurahan')
        {
            // semua akun pembina kelurahan pada 1 kecamatan
            $query = "SELECT * FROM petugas WHERE kecamatan_id = $petugas->kecamatan_id AND user_id IN (SELECT user_id FROM user_roles WHERE role_id=$role->id)";
        }
        else
        {
            // semua akun sesuai dengan roles
            $query = "SELECT * FROM petugas WHERE user_id IN (SELECT user_id FROM user_roles WHERE role_id=$role->id)";
        }
    }

    $db->query = $query;
    $items = $db->exec('all');
    $clause_dest_item = array_map(function($i){
        return $i->nama;
    }, $items);

    $clause_dest_item = implode(',',$clause_dest_item);

    $_POST[$table]['clause_dest_item'] = $clause_dest_item;

    $insert = $db->insert($table,$_POST[$table]);
        
    foreach($items as $item)
    {
        $db->insert('feedback_receivers',['feedback_id'=>$insert->id,'user_id'=>$item->user_id]);
    }

    set_flash_msg(['success'=>'Umpan balik berhasil ditambahkan']);
    header('location:'.routeTo('feedbacks/index'));
    die();
}

return compact('table','error_msg','old','fields','data');