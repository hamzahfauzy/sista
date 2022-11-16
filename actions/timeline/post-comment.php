<?php

$conn = conn();
$db   = new Database($conn);

if(request() == 'POST')
{
    $user = auth()->user;
    $post = $db->insert('comments',[
        'post_id' => $_GET['id'],
        'user_id' => $user->id,
        'content' => $_POST['content'],
        'status' => 'Publish' // $_POST['status'],
    ]);

    set_flash_msg(['success'=>'Komentar berhasil di publish']);
    header('location:'.routeTo('timeline/detail',['id'=>$_GET['id']]));
    die();
}