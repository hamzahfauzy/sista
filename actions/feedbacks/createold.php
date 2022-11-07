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

// $fields['content']['type'] = 'options:'.implode('|',$instructures);

$user = auth()->user;
if(get_role($user->id)->name == 'pembina kecamatan')
{
    unset($fields['clause_dest']);
    unset($fields['clause_dest_item']);
    unset($fields['kecamatan_id']);
    
    $petugas = $db->single('petugas',['user_id'=>$user->id]);

    $fields['kelurahan_id']['type'] = 'options-obj:kelurahan,id,nama,kecamatan_id,'.$petugas->kecamatan_id;
}

else
{
    if(!isset($_GET['feedbacks']))
    {
        unset($fields['kecamatan_id']);
        unset($fields['kelurahan_id']);
        unset($fields['lingkungan_id']);
        unset($fields['content']);
        unset($fields['topik']);
    }
    else
    {
        if($data->clause_dest == 'pembina kecamatan' && $data->clause_dest_item != 'Semua')
        {
            unset($fields['kecamatan_id']);
            
            $petugas = $db->single('petugas',['user_id'=>$data->clause_dest_item]);
            $fields['kelurahan_id']['type'] = 'options-obj:kelurahan,id,nama,kecamatan_id,'.$petugas->kecamatan_id;
        }
        else
        {
            // unset($fields['kecamatan_id']);
            // unset($fields['kelurahan_id']);
            // unset($fields['lingkungan_id']);
        }
    }
}

if(isset($_GET['feedbacks']['clause_dest']) && isset($_GET['feedbacks']['clause_dest_item']))
{
    $fields['clause_dest']['type'] = 'text';
    $fields['clause_dest_item']['type'] = 'text';

    if($data->clause_dest_item != 'Semua')
    {
        $users = $db->single('users',['id'=>$data->clause_dest_item]);
        $data->clause_dest_item = $users->name;
    }
    
}


// print_r($fields);

// $add_on = [
//     'dest' => [
//         'label' => 'Kepada',
//         'type'  => 'options:pilih|pembina kabupaten|pembina kecamatan'
//     ],
//     'dest_item' => [
//         'label' => 'Tujuan',
//         'type'  => 'options:pilih'
//     ]
// ];

// $fields = array_merge($add_on, $fields);

if(request() == 'POST')
{

    if($data->clause_dest == 'pembina kecamatan' && $_GET['feedbacks']['clause_dest_item'] != 'Semua')
    {
        $petugas = $db->single('petugas',['user_id' => $_GET['feedbacks']['clause_dest_item']]);
        $_POST[$table]['kecamatan_id'] = $petugas->kecamatan_id;
    }

    if(get_role($user->id)->name == 'pembina kecamatan')
    {
        $petugas = $db->single('petugas',['user_id' => $user->id]);
        $_POST[$table]['kecamatan_id'] = $petugas->kecamatan_id;
        $_POST[$table]['clause_dest']  = "pembina kelurahan";
    }

    $_POST[$table]['user_id'] = auth()->user->id;

    $insert = $db->insert($table,$_POST[$table]);

    if(get_role($user->id)->name == 'pembina kecamatan')
    {
        $role = $db->single('roles',['name' => 'pembina kelurahan']);
        $db->query = "SELECT user_id FROM petugas WHERE kelurahan_id = ".$_POST[$table]['kelurahan_id']." AND user_id IN (SELECT user_id FROM user_roles WHERE role_id=$role->id)";
        $users = $db->exec('all');
        
        foreach($users as $user)
        {
            $db->insert('feedback_receivers',['feedback_id'=>$insert->id,'user_id'=>$user->user_id]);
        }
    }
    else
    {
        if($insert->clause_dest_item != 'Semua')
        {
            $db->insert('feedback_receivers',['feedback_id'=>$insert->id,'user_id'=>$_GET['feedbacks']['clause_dest_item']]);
        }
        else
        {
            $role = $db->single('roles',['name' => $insert->clause_dest]);
            $db->query = "SELECT id, name FROM users WHERE id IN (SELECT user_id FROM user_roles WHERE role_id=$role->id)";
            $users = $db->exec('all');
            
            foreach($users as $user)
            {
                $db->insert('feedback_receivers',['feedback_id'=>$insert->id,'user_id'=>$user->id]);
            }
        }
    }

    set_flash_msg(['success'=>'Umpan balik berhasil ditambahkan']);
    header('location:'.routeTo('feedbacks/index'));
    die();
}

return compact('table','error_msg','old','fields','data');