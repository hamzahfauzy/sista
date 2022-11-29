<?php

$conn = conn();
$db   = new Database($conn);

if(request() == 'POST')
{
    $user = auth()->user;
    $post = $db->insert('comments',[
        'post_id' => $_POST['post_id'],
        'user_id' => $user->id,
        'content' => $_POST['content'],
        'status' => 'Publish' // $_POST['status'],
    ]);

    echo json_encode([
        'status' => 'success'
    ]);
}
echo json_encode([
    'status' => 'fail'
]);
die();