<?php

$table = 'feedbacks';
Page::set_title(ucwords($table));
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$error_msg = get_flash_msg('error');
$fields = config('fields')[$table];
$user = auth()->user;

unset($fields['content']);
if(get_role($user->id)->name == 'pembina kecamatan')
{
    unset($fields['clause_dest']);
}

$actions = [];

$data = $db->all($table,[],['id'=>'desc']);
$roles = array_map(function($role){
    return $role->name;
}, get_roles($user->id));

if(in_array('instruktur',$roles))
{
    $data = $db->all($table,[
        'user_id' => $user->id
    ]);
}


if((in_array('pembina kabupaten',$roles) && !in_array('instruktur',$roles) || in_array('pembina kecamatan',$roles)))
{
    $own = $db->all($table,[
        'user_id' => $user->id
    ],['id'=>'desc']);

    $receiver = $db->all('feedback_receivers',[
        'user_id' => $user->id
    ],['id'=>'desc']);

    $receiver = array_map(function($r) use ($db) {
        return $db->single('feedbacks',['id'=>$r->feedback_id]);
    }, $receiver);

    $data = array_merge($receiver, $own);
}

if(in_array('pembina kelurahan',$roles))
{
    $receiver = $db->all('feedback_receivers',[
        'user_id' => $user->id
    ],['id'=>'desc']);

    $data = array_map(function($r) use ($db) {
        return $db->single('feedbacks',['id'=>$r->feedback_id]);
    }, $receiver);
}

$db->update('feedback_receivers',[
    'status'  => 1
],[
    'user_id' => $user->id,
]);

return [
    'datas' => $data,
    'table' => $table,
    'success_msg' => $success_msg,
    'error_msg' => $error_msg,
    'fields' => $fields,
    'actions' => $actions
];