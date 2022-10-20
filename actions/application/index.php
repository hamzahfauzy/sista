<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('application');
$success_msg = get_flash_msg('success');

if(request() == 'POST')
{
    if(isset($_POST['submit_app']))
    {
        $db->update('application',$_POST['app'],[
            'id' => $data->id
        ]);
    
        set_flash_msg(['success'=>'Detail Aplikasi berhasil diupdate']);
    }
    else if(isset($_POST['submit_cache']))
    {
        array_map( 'unlink', array_filter((array) glob("cached/rekapitulasi/*") ) );
        set_flash_msg(['success'=>'Rekapitulasi berhasil di refresh']);
    }
    header('location:'.routeTo('application/index'));
    die();
}

return compact('data','success_msg');