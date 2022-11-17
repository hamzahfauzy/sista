<?php

$table = 'posts';
Page::set_title(ucwords($table));
$conn = conn();
$db   = new Database($conn);
$db->query = "SELECT * FROM $table WHERE status='Publish' ORDER BY id DESC LIMIT 0,20";
$posts = $db->exec('all');
$user = auth()->user;
$posts = array_map(function($post) use ($db, $user){
    $post->user = $db->single('users',['id' => $post->user_id]);
    $post->date = tgl_indo($post->created_at, true);
    $files = $db->all('post_files',['post_id'=>$post->id]);
    $post->files = $files; // array_chunk($files, 2);
    $post->comment_count = $db->exists('comments',['post_id'=>$post->id]);
    $post->post_response = $db->single('post_responses',['post_id'=>$post->id,'user_id'=>$user->id]);
    $post->post_response_like_count = $db->exists('post_responses',['post_id'=>$post->id,'response_type'=>'like']);
    $post->post_response_dislike_count = $db->exists('post_responses',['post_id'=>$post->id,'response_type'=>'dislike']);
    return $post;
},$posts);
$count = $db->exists($table,['status'=>'Publish']);
$success_msg = get_flash_msg('success');

if(user_notif_counter())
{
    $db->update('post_notif_counter',[
        'post_counter' => all_notif()
    ],[
        'user_id' => $user->id
    ]);
}
else
{
    $db->insert('post_notif_counter',[
        'post_counter' => all_notif(),
        'user_id' => $user->id
    ]);
}

return compact('success_msg', 'posts','count');