<?php

$table = 'survey';
$user = auth()->user;

$conn = conn();
$db   = new Database($conn);
$fields = config('fields')[$table];

$draw    = $_GET['draw'];
$start   = $_GET['start'];
$length  = $_GET['length'];
$search  = $_GET['search']['value'];
$order   = $_GET['order'];

$columns = config('fields')[$table];
$columns = array_keys($columns);
$columns[] = 'status';

$order_by = " ORDER BY ".$columns[$order[0]['column']]." ".$order[0]['dir'];

$where = "";

if(!empty($search))
{
    $where = "WHERE no_kk LIKE '%$search%'";
}

if(!in_array(get_role($user->id)->name,['administrator','bupati']))
{
    $petugas = $db->single('petugas',['user_id' => $user->id]);
    $db->query = "SELECT no_kk FROM penduduk WHERE kecamatan_id = $petugas->kecamatan_id GROUP BY no_kk";
    $all_kk = $db->exec('all');
    $all_kk = array_map(function($a){
        return $a->no_kk;
    }, $all_kk);
    if($all_kk)
    {
        $where .= (empty($where) ? 'WHERE ' : 'AND ') . "no_kk IN ('".implode("','",$all_kk)."')";

        if(get_role($user->id)->name == 'surveyor')
        {
            $where .= (empty($where) ? 'WHERE ' : 'AND ') . "user_id = $user->id";
        }
    }
}

$db->query = "SELECT COUNT(*) as TOTAL FROM $table $where $order_by";
$total = $db->exec('single');
$db->query = "SELECT * FROM $table $where $order_by LIMIT $start,$length";
$data  = $db->exec('all');

$results = [];

foreach($data as $key => $d)
{
    $results[$key][] = $key+$start+1;
    foreach($columns as $col)
    {
        $results[$key][] = $d->{$col};
    }

    $action = '';
    if(is_allowed('survey/view',auth()->user->id)):
        $action .= '<a href="'.routeTo('survey/view',['id' => $d->id]).'" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Lihat</a>';
    endif;
    if($d->status == 'draft'):
        if(is_allowed(get_route_path('survey/publish',[]),auth()->user->id)):
            $action .= '<button onclick="publishData('.$d->id.')" class="btn btn-sm btn-warning"><i class="fas fa-check"></i> Publish</button>';
        endif;
        if(is_allowed(get_route_path('survey/edit',[]),auth()->user->id)):
            $action .= '<a href="'.routeTo('survey/edit',['id'=>$d->id]).'" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i> Edit</a>';
        endif;
        if(is_allowed(get_route_path('survey/delete',[]),auth()->user->id)):
            $action .= '<button onclick="deleteData('.$d->id.')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>';
        endif;
    endif;
    $results[$key][] = $action;
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => (int)$total->TOTAL,
    "recordsFiltered" => (int)$total->TOTAL,
    "data" => $results
]);

die();