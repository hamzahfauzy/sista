<?php

$conn = conn();
$db   = new Database($conn);

$kecamatan = $db->all('kecamatan');
$content = "";

if(isset($_GET['tampil']) || isset($_GET['print']))
{
    if(isset($_GET['kecamatan_id']) && $_GET['kecamatan_id'] != '*')
    {
        if(isset($_GET['kelurahan_id']) && $_GET['kelurahan_id'] != '*')
        {
            if(isset($_GET['lingkungan_id']) && $_GET['lingkungan_id'] != '*')
            {
                $content = (new Rekap)->kasus($_GET['tahun'],'lingkungan',$_GET['lingkungan_id']);
                return compact('kecamatan','content');
            }

            $content = (new Rekap)->kasus($_GET['tahun'],'kelurahan',$_GET['kelurahan_id']);
            return compact('kecamatan','content');
        }

        $content = (new Rekap)->kasus($_GET['tahun'],'kecamatan',$_GET['kecamatan_id']);
        return compact('kecamatan','content');
    }

    $content = (new Rekap)->kasus($_GET['tahun']);
    return compact('kecamatan','content');
}

return compact('kecamatan','content');