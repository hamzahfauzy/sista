<?php

$success_msg = get_flash_msg('success');
$error_msg = get_flash_msg('error');

if(request() == 'POST')
{
    $conn  = conn();
    $db    = new Database($conn);

    $user = $db->single('users',[
        'username' => $_POST['username'],
    ]);

    if($user)
    {
        set_flash_msg(['error'=>'Pendaftaran Gagal! NIK sudah terdaftar']);
        header('location:'.routeTo('auth/register'));
        die();
    }

    $user = $db->insert('users',[
        'name' => $_POST['name'],
        'username' => $_POST['username'],
        'password' => md5($_POST['password']),
    ]);

    $db->insert('user_roles',[
        'role_id' => 7,
        'user_id' => $user->id
    ]);

    set_flash_msg(['success'=>'Pendaftaran Berhasil! Silahkan masukkan NIK sebagai username']);
    header('location:'.routeTo('auth/login'));
    die();

}

return [
    'success_msg' => $success_msg,
    'error_msg' => $error_msg,
];