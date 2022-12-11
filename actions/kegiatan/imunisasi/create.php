<?php

$table = 'imunisasi';
Page::set_title('Tambah '.ucwords($table));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $insert = $db->insert($table,$_POST[$table]);

    set_flash_msg(['success'=>$table.' berhasil ditambahkan']);
    header('location:'.routeTo('kegiatan/imunisasi/index'));
}

return compact('table','error_msg','old','fields');