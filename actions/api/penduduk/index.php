<?php

$auth  = auth();
$conn  = conn();
$db    = new Database($conn);

$draw   = $_GET['draw'];
$start  = $_GET['start'];
$length = $_GET['length'];
$search = $_GET['search']['value'];
$order  = $_GET['order'];

$columns = [
    'contents',
    'sent_at',
    'user_name',
    'is_loop',
    'active_status',
];

$order_by = " ORDER BY ".$columns[$order[0]['column']]." ".$order[0]['dir'];

$where = !empty($search) ? "WHERE (contents LIKE '%$search%' OR created_at LIKE '%$search%' OR user_name LIKE '%$search%')" : '';
if(empty($_GET['type']))
    if(!empty($search))
        $where .= " AND (user_name = '$auth->nama' OR user_id = $auth->user_id)";
    else
        $where = " WHERE (user_name = '$auth->nama' OR user_id = $auth->user_id)";
else
{
    if($_GET['type'] == 'employee')
        if(!empty($search))
            $where .= " AND user_name IS NOT NULL AND sent_at IS NULL";
        else
            $where = " WHERE user_name IS NOT NULL AND sent_at IS NULL";
    elseif($_GET['type'] == 'broadcast')
        if(!empty($search))
            $where .= " AND user_name IS NULL AND sent_at IS NULL";
        else
            $where = " WHERE user_name IS NULL AND sent_at IS NULL";
    else
        if(!empty($search))
            $where .= " AND sent_at IS NOT NULL";
        else
            $where = " WHERE sent_at IS NOT NULL";
}


$db->query = "SELECT COUNT(*) as TOTAL FROM notifications $where $order_by";
$total = $db->exec('single');
$db->query = "SELECT * FROM notifications $where $order_by LIMIT $start,$length";
$notifications  = $db->exec('all');
$results = [];

foreach($notifications as $key => $notification)
{
    $results[$key][] = $notification->contents;
    $results[$key][] = $notification->sent_at??$notification->created_a;
    $results[$key][] = $notification->user_name != null ? $notification->user_name : 'Semua Pengguna';
    $results[$key][] = $notification->is_loop ? 'Ya' : 'Tidak';
    $action = '';
    if($auth->role_name == 'Admin')
    {
        $results[$key][] = '
        <select class="form-control" onchange="updateStatus('.$notification->id.',this.value)">
            <option value="1" '.($notification->active_status?'selected=""':'').'>Ya</option>
            <option value="0" '.(!$notification->active_status?'selected=""':'').'>Tidak</option>
        </select>';
        if($notification->sent_at)
            $action .= '<a href="index.php?r=notifications/update&id='.$notification->id.'" class="btn btn-warning text-strong"><i class="ti ti-pencil"></i></a>';
        $action .= '<a href="index.php?action=notifications/delete&id='.$notification->id.'" class="btn btn-danger text-strong"><i class="ti ti-trash"></i></a>';
    }
    else
    {
        $results[$key][] = $notification->active_status?'Ya':'Tidak';
        $action = "-";
    }
    $results[$key][] = $action;
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => (int)$total->TOTAL,
    "recordsFiltered" => (int)$total->TOTAL,
    "data" => $results
]);

die();