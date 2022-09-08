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
