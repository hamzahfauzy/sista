<?php

class Iks
{

    function __construct()
    {
        $conn = conn();
        $this->db = new Database($conn);
    }

    function byPenduduk($tahun, $no_kk)
    {
        $db = $this->db;
        $iks = $db->single('iks_penduduk',[
            'tahun' => $tahun,
            'no_kk' => $no_kk
        ]);

        if($iks)
        {
            return number_format($iks->skor*100,3);
        }

        return 0;
    }

    function byLingkungan($tahun, $lingkungan_id)
    {
        $db = $this->db;
        $db->query = "SELECT SUM(skor) as hasil_iks FROM iks_penduduk WHERE status='publish' AND tahun='$tahun' AND lingkungan_id=$lingkungan_id";
        $iks = $db->exec('single');

        if($iks)
        {
            $penduduk = $db->exists('penduduk',['lingkungan_id'=>$lingkungan_id]);
            return number_format(($iks->hasil_iks/$penduduk)*100,3);
        }

        return 0;
    }

    function byKelurahan($tahun, $kelurahan_id)
    {
        $db = $this->db;
        $db->query = "SELECT SUM(skor) as hasil_iks FROM iks_penduduk WHERE status='publish' AND tahun='$tahun' AND kelurahan_id=$kelurahan_id";
        $iks = $db->exec('single');

        if($iks)
        {
            $penduduk = $db->exists('penduduk',['kelurahan_id'=>$kelurahan_id]);
            return number_format(($iks->hasil_iks/$penduduk)*100,3);
        }

        return 0;
    }
    
    function byKecamatan($tahun, $kecamatan_id)
    {
        $db = $this->db;
        $db->query = "SELECT SUM(skor) as hasil_iks FROM iks_penduduk WHERE status='publish' AND tahun='$tahun' AND kecamatan_id=$kecamatan_id";
        $iks = $db->exec('single');

        if($iks)
        {
            $penduduk = $db->exists('penduduk',['kecamatan_id'=>$kecamatan_id]);
            return number_format(($iks->hasil_iks/$penduduk)*100,3);
        }

        return 0;
    }

    function all($tahun)
    {
        $db = $this->db;
        $db->query = "SELECT SUM(skor) as hasil_iks FROM iks_penduduk WHERE status='publish' AND tahun='$tahun'";
        $iks = $db->exec('single');

        if($iks)
        {
            $num_of_kec = $db->exists('kecamatan');
            $penduduk = $db->exists('penduduk');
            return number_format(($iks->hasil_iks/$penduduk)/$num_of_kec*100,3);
        }

        return 0;
    }
}