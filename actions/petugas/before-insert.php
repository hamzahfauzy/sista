<?php

$user = $db->insert('users',[
    'name' => $_POST['petugas']['nama'],
    'username' => $_POST['petugas']['NIK'],
    'password' => md5(123456),
]);

$db->insert('user_roles',[
    'user_id' => $user->id,
    'role_id' => $_POST['petugas']['sebagai'],
]);

$_POST['petugas']['user_id'] = $user->id;
unset($_POST['petugas']['sebagai']);