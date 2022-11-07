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
    'clause_dest' => isset($_GET['feedbacks']['clause_dest'])?$_GET['feedbacks']['clause_dest']:'',
    'clause_dest_item' => isset($_GET['feedbacks']['clause_dest_item'])?$_GET['feedbacks']['clause_dest_item']:'',
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
    $fields['clause_dest_item']['type'] = 'options-obj:kelurahan,id,nama,kecamatan_id,'.$petugas->kecamatan_id;
}


if(request() == 'POST')
{
    // $req = $_POST[$table];
    // $req['clause_dest'] = strtolower($req['clause_dest']);
    
    // $clause_dest_item = implode(",",$req['clause_dest_item']);

    // $_POST[$table]['clause_dest'] = $req['clause_dest'];
    // $_POST[$table]['clause_dest_item'] = $clause_dest_item;
    // $_POST[$table]['user_id'] = auth()->user->id;

    // $insert = $db->insert($table,$_POST[$table]);

    // if(get_role($user->id)->name == 'pembina kecamatan')
    // {
    //     $role = $db->single('roles',['name' => 'pembina kelurahan']);
        
    //     if($req['clause_dest_item'][0] == 'Semua')
    //     {
    //         $db->query = "SELECT user_id FROM petugas WHERE kecamatan_id = ".$petugas->kecamatan_id." AND user_id IN (SELECT user_id FROM user_roles WHERE role_id=$role->id)";
    //     }
    //     else
    //     {
    //         $db->query = "SELECT user_id FROM petugas WHERE kelurahan_id IN ($clause_dest_item) AND user_id IN (SELECT user_id FROM user_roles WHERE role_id=$role->id)";
    //     }
        
    //     $users = $db->exec('all');
        
    //     foreach($users as $user)
    //     {
    //         $db->insert('feedback_receivers',['feedback_id'=>$insert->id,'user_id'=>$user->user_id]);
    //     }
    // }
    // else
    // {
    //     $role = $db->single('roles',['name' => $insert->clause_dest]);
    //     if($req['clause_dest_item'][0] != 'Semua')
    //     {
    //         $db->query = "SELECT user_id FROM petugas WHERE user_id IN (SELECT user_id FROM user_roles WHERE role_id=$role->id)";
    //     }
    //     else
    //     {
    //         $db->query = "SELECT user_id FROM petugas WHERE user_id IN ($clause_dest_item)";
    //     }
        
    //     $users = $db->exec('all');
        
    //     foreach($users as $user)
    //     {
    //         $db->insert('feedback_receivers',['feedback_id'=>$insert->id,'user_id'=>$user->user_id]);
    //     }
    // }

    set_flash_msg(['success'=>'Umpan balik berhasil ditambahkan']);
    header('location:'.routeTo('feedbacks/index'));
    die();
}

return compact('table','error_msg','old','fields','data');