<?php

if(isset($_FILES['file']) && !empty($_FILES['file']['name']))
{
    $ext  = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $exts = ['jpg','jpeg','pdf','png'];
    if(!in_array($ext,$exts))
    {
        set_flash_msg(['error'=>'Format file tidak sesuai (harus memiliki ekstensi seperti '.implode(',',$exts).')']);
        header('location:'.routeTo('crud/create',['table'=>'posyandu']));
        die();
    }
    $name = strtotime('now').'.'.$ext;
    $file = 'uploads/'.$name;
    copy($_FILES['file']['tmp_name'],$file);
    
    $_POST['posyandu']['file'] = $file;
}