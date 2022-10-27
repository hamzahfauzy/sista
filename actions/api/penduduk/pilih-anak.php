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

$columns = ['NIK','nama','alamat'];

$order_by = " ORDER BY ".$columns[$order[0]['column']]." ".$order[0]['dir'];

$where = "WHERE NIK <> ''";

if(!empty($search))
{
    $where .= " AND (NIK LIKE '%$search%' OR no_kk LIKE '%$search%' OR nama LIKE '%$search%' OR alamat LIKE '%$search%')";
}

$user = auth()->user;

if(!in_array(get_role($user->id)->name,['administrator','pembina kabupaten','bupati']))
{

    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kecamatan_id = $petugas->kecamatan_id;
    if(!empty($kecamatan_id))
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
    $action = '';
    $action .= '<input type="checkbox" name="nik_anak[]" id="NIK-'.$d->NIK.'" onclick="appendAnak(\''.$d->NIK.'\')" class="nik_anak" value="'.$d->NIK.'">';
    $results[$key][] = $action;
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
}

echo json_encode([
    "draw" => $draw,
    "recordsTotal" => (int)$total->TOTAL,
    "recordsFiltered" => (int)$total->TOTAL,
    "data" => $results
]);

die();
