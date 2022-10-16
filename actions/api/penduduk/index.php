<?php

$table   = 'penduduk';
$auth    = auth();
$conn    = conn();
$db      = new Database($conn);

$draw    = $_GET['draw'];
$start   = $_GET['start'];
$length  = $_GET['length'];
$search  = $_GET['search']['value'];
$order   = $_GET['order'];

$columns = config('fields')[$table];
$columns = array_keys($columns);

$order_by = " ORDER BY ".$columns[$order[0]['column']]." ".$order[0]['dir'];

$where = "";

if(!empty($search))
{
    $where = "WHERE (NIK LIKE '%$search%' OR no_kk LIKE '%$search%' OR nama LIKE '%$search%' OR alamat LIKE '%$search%')";
}

$user = auth()->user;

if(!in_array(get_role($user->id)->name,['administrator','bupati']))
{

    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kecamatan_id = $petugas->kecamatan_id;
    if(!empty($petugas->kelurahan_id))
    {
        $str = "kelurahan_id=$petugas->kelurahan_id";
        $where .= $where ? " AND ".$str : "WHERE ".$str;
    }

    else if(!empty($kecamatan_id))
    {
        $str = "kecamatan_id=$petugas->kecamatan_id";
        $where .= $where ? " AND ".$str : "WHERE ".$str;
    }

    else
    {
        $str = "kecamatan_id=0";
        $where .= $where ? " AND ".$str : "WHERE ".$str;
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
        if(in_array($col,['kecamatan_id','kelurahan_id','lingkungan_id']))
        {
            $tbl = str_replace('_id','',$col);
            $r   = $db->single($tbl,['id' => $d->{$col}]);
            $results[$key][] = $r->nama;
        }
        else
        {
            $results[$key][] = $d->{$col};
        }
    }

    $action = '';
    if(is_allowed(get_route_path('crud/edit',['table'=>$table]),auth()->user->id)):
        $action .= '<a href="'.routeTo('crud/edit',['table'=>$table,'id'=>$d->id]).'" class="btn btn-sm btn-warning"><i class="fas fa-pencil-alt"></i> Edit</a>';
    endif;
    if(is_allowed(get_route_path('crud/delete',['table'=>$table]),auth()->user->id)):
        $action .= '<a href="'.routeTo('crud/delete',['table'=>$table,'id'=>$d->id]).'" onclick="if(confirm(\'apakah anda yakin akan menghapus data ini ?\')){return true}else{return false}" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>';
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