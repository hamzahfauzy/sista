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
                $content = (new Rekapitulasi)->lingkungan();
                return compact('kecamatan','content');
            }

            $content = (new Rekapitulasi)->kelurahan();
            return compact('kecamatan','content');
        }

        $content = (new Rekapitulasi)->kecamatan();
        return compact('kecamatan','content');
    }

    $content = (new Rekapitulasi)->index();
    return compact('kecamatan','content');
}

return compact('kecamatan','content');