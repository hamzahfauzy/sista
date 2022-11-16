<?php

$table = 'posts';
Page::set_title(ucwords($table));
$conn = conn();
$db   = new Database($conn);
$db->query = "SELECT * FROM $table WHERE status='Publish' ORDER BY id DESC LIMIT 0,20";
$posts = $db->exec('all');
$posts = array_map(function($post) use ($db){
    $post->user = $db->single('users',['id' => $post->user_id]);
    $post->date = tgl_indo($post->created_at, true);
    $files = $db->all('post_files',['post_id'=>$post->id]);
    $post->files = $files; // array_chunk($files, 2);
    return $post;
},$posts);
$count = $db->exists($table,['status'=>'Publish']);
$success_msg = get_flash_msg('success');

return compact('success_msg', 'posts','count');