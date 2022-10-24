<?php

$conn = conn();
$db   = new Database($conn);

$kecamatan = $db->all('kecamatan');
$content = "";

if(isset($_GET['tampil']))
{
    if(isset($_GET['kecamatan_id']) && $_GET['kecamatan_id'] != '*')
    {
        if(isset($_GET['kelurahan_id']) && $_GET['kelurahan_id'] != '*')
        {
            if(isset($_GET['lingkungan_id']) && $_GET['lingkungan_id'] != '*')
            {
                $content = (new Rekap)->lingkungan();
                return compact('kecamatan','content');
            }

            $content = (new Rekap)->kelurahan();
            return compact('kecamatan','content');
        }

        $content = (new Rekap)->kecamatan();
        return compact('kecamatan','content');
    }

    $content = (new Rekap)->index();
    return compact('kecamatan','content');
}

return compact('kecamatan','content');