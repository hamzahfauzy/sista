<?php

$success_msg = get_flash_msg('success');
$error_msg = get_flash_msg('error');

if(request() == 'POST')
{
    $conn  = conn();
    $db    = new Database($conn);

    $user = $db->single('users',[
        'username' => $_POST['username'],
        'password' => md5($_POST['password']),
    ]);

    if($user)
    {
        Session::set(['user_id'=>$user->id]);
        if(get_role($user->id)->name=='penduduk')
        {
            header('location:'.routeTo('default/riwayat',['nik'=>$user->username]));
        }
        else
        {
            header('location:'.routeTo('default/index'));
        }
        die();
    }

    set_flash_msg(['error'=>'Login Gagal! Nama Pengguna atau Kata Sandi tidak cocok']);
    header('location:'.routeTo('auth/login'));
    die();
}

return [
    'success_msg' => $success_msg,
    'error_msg' => $error_msg,
];