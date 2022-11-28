<?php

$conn = conn();
$db   = new Database($conn);

if(request() == 'POST' && auth()->user)
{
    $user = auth()->user;
    $post = $db->insert('posts',[
        'user_id' => $user->id,
        'content' => $_POST['content'],
        'status' => 'Publish' // $_POST['status'],
    ]);

    if(isset($_FILES['files']) && $_FILES['files']["name"][0] != "")
    {
        $files = do_upload($_FILES['files'],'uploads',false, true);
        foreach($files as $file)
        {
            $db->insert('post_files',[
                'post_id' => $post->id,
                'file_url' => $file
            ]);
        }
    }

    echo json_encode([
        'status' => 'success'
    ]);
}
echo json_encode([
    'status' => 'fail'
]);
die();