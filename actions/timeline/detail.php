<?php

$table = 'posts';
$conn = conn();
$db   = new Database($conn);

$user = auth()->user;

$post = $db->single($table,['id' => $_GET['id']]);
$post->user = $db->single('users',['id' => $post->user_id]);
$post->date = tgl_indo($post->created_at, true);
$post->comment_count = $db->exists('comments',['post_id'=>$post->id]);
$files = $db->all('post_files',['post_id'=>$post->id]);
$post->files = $files;
$post->post_response = $db->single('post_responses',['post_id'=>$post->id,'user_id'=>$user->id]);
$post->post_response_like_count = $db->exists('post_responses',['post_id'=>$post->id,'response_type'=>'like']);
$post->post_response_dislike_count = $db->exists('post_responses',['post_id'=>$post->id,'response_type'=>'dislike']);
$title =  'Detail Timeline - '.$post->user->name.' '.$post->date;
Page::set_title($title);
$success_msg = get_flash_msg('success');

return compact('post','title','success_msg');