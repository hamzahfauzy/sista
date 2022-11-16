<?php

$conn = conn();
$db   = new Database($conn);

if(request() == 'POST')
{
    $user = auth()->user;
    // check response
    $check = $db->single('post_responses',[
        'post_id' => $_POST['post_id'],
        'user_id' => $user->id,
    ]);
    if($check)
    {
        $db->update('post_responses',[
            'response_type' => $_POST['response_type']
        ],[
            'id' => $check->id
        ]);
    }
    else
    {
        $db->insert('post_responses',[
            'post_id' => $_POST['post_id'],
            'user_id' => $user->id,
            'response_type' => $_POST['response_type']
        ]);
    }

    $post_response_like_count = $db->exists('post_responses',['post_id'=>$_POST['post_id'],'response_type'=>'like']);
    $post_response_dislike_count = $db->exists('post_responses',['post_id'=>$_POST['post_id'],'response_type'=>'dislike']);

    echo json_encode([
        'status' => 'success',
        'data' => [
            'post_like_count' => $post_response_like_count,
            'post_dislike_count' => $post_response_dislike_count,
        ]
    ]);
    die();
}