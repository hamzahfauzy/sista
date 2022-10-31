<?php

Page::set_title('Rekapitulasi Penduduk');
$fields = config('fields')['penduduk'];

unset($fields['sebagai']);
unset($fields['kecamatan_id']);
unset($fields['kelurahan_id']);
unset($fields['lingkungan_id']);

$fields['status'] = [
    'label' => 'Status',
    'type'  => 'text'
];

$datas = [];

$conn = conn();
$db   = new Database($conn);

$kecamatan = $db->all('kecamatan');
$content = "";

$status = isset($_GET['status']) ? $_GET['status'] : 'semua';

if(isset($_GET['tampil']) || isset($_GET['print']))
{
    if(isset($_GET['kecamatan_id']) && $_GET['kecamatan_id'] != '*')
    {
        if(isset($_GET['kelurahan_id']) && $_GET['kelurahan_id'] != '*')
        {
            if(isset($_GET['lingkungan_id']) && $_GET['lingkungan_id'] != '*')
            {
                $content = (new Rekap)->penduduk($_GET['tahun'],'lingkungan',$_GET['lingkungan_id'],$status);
                echo $content;
                die();
            }

            $content = (new Rekap)->penduduk($_GET['tahun'],'kelurahan',$_GET['kelurahan_id'],$status);
            echo $content;;
            die();
        }

        $content = (new Rekap)->penduduk($_GET['tahun'],'kecamatan',$_GET['kecamatan_id'],$status);
        echo $content;
        die();
    }

    $content = (new Rekap)->penduduk($_GET['tahun'],false,false,$status);
    echo $content;
    die();
}

return compact('kecamatan','datas','fields');