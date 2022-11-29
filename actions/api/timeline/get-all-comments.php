<?php
$table = 'posts';
$conn = conn();
$db   = new Database($conn);
$post_id = $_GET['post_id'];

$comments = $db->all('comments',['post_id'=>$post_id]);
$comments = array_map(function($comment) use ($db){
    $comment->user = $db->single('users',['id' => $comment->user_id]);
    $comment->date = tgl_indo($comment->created_at, true);
    return $comment;
}, $comments);

echo json_encode([
    'status' => 'success',
    'data'   => $comments
]);
die();