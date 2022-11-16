<?php

$table = 'posts';
$conn = conn();
$db   = new Database($conn);

$post = $db->single($table,['id' => $_GET['id']]);
$post->user = $db->single('users',['id' => $post->user_id]);
$post->date = tgl_indo($post->created_at, true);
$files = $db->all('post_files',['post_id'=>$post->id]);
$post->files = $files;

$comments = $db->all('comments',['post_id'=>$post->id]);
$comments = array_map(function($comment) use ($db){
    $comment->user = $db->single('users',['id' => $comment->user_id]);
    $comment->date = tgl_indo($comment->created_at, true);
    return $comment;
}, $comments);
$post->comments = $comments;
$title =  'Detail Timeline - '.$post->user->name.' '.$post->date;
Page::set_title($title);
$success_msg = get_flash_msg('success');

return compact('post','title','success_msg');