<?php

$table = 'feedbacks';
$conn = conn();
$db   = new Database($conn);

$data = $db->exists($table,[
    'user_id' => auth()->user->id,
    'id' => $_GET['id']
]);

if(!$data || empty($data))
{
    set_flash_msg(['error'=>'Gagal']);
    header('location:'.routeTo('feedbacks/index'));
    die();
}

$db->delete($table,[
    'id' => $_GET['id']
]);

$db->delete('feedback_receivers',[
    'feedback_id' => $_GET['id']
]);

set_flash_msg(['success'=>'Umpan balik berhasil dihapus']);
header('location:'.routeTo('feedbacks/index'));
die();