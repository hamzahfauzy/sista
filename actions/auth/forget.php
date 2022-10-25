<?php

$success_msg = get_flash_msg('success');
$error_msg = get_flash_msg('error');

if(request() == 'POST')
{
    $conn  = conn();
    $db    = new Database($conn);

    $user = $db->single('petugas',[
        'email' => $_POST['email']
    ]);

    if($user)
    {
        $new_password = randomPassword();
        $db->update('users', [
            'password' => md5($new_password)
        ],[
            'id' => $user->user_id
        ]);
        $mail = (new Mail)->send($user->email,'Reset Password Pak Surya-Taufik Asahan','Silahkan login ke aplikasi '.routeTo('auth/login').' dengan menggunakan password baru anda yaitu <b>'.$new_password.'</b>');
        if($mail['status'] == 'success')
        {
            set_flash_msg(['success'=>'Reset Password Berhasil! Silahkan cek email anda']);
        }
        else
        {
            set_flash_msg(['error'=>$mail['message']]);
        }
    }
    else
    {
        set_flash_msg(['error'=>'Reset Password Gagal! Email tidak ditemukan']);
    }

    header('location:'.routeTo('auth/forget'));
    die();
}

return [
    'success_msg' => $success_msg,
    'error_msg' => $error_msg,
];