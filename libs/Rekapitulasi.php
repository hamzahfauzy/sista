<?php

class Rekapitulasi {

    function __construct()
    {
        $conn = conn();
        $this->db   = new Database($conn);
    }

    function index()
    {
        $db = $this->db;
        $periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        $cachefile = 'cached/rekapitulasi/index-'.$periode.'.html';
        if (file_exists($cachefile) && !isset($_GET['nocache'])) {
            ob_start();
            readfile($cachefile);
            return ob_get_clean();
        }

        $all_kecamatan = $db->all('kecamatan');
        $all_kelurahan = $db->all('kelurahan');
        $all_lingkungan = $db->all('lingkungan');

        $kecamatan  = count($all_kecamatan);
        $kelurahan  = count($all_kelurahan);
        $lingkungan = count($all_lingkungan);
        $penduduk = $db->exists('penduduk');

        $iks = array_map(function($k) use ($db, $periode){
            $counter = 0;
            $total_iks = 0;
            $db->query = "SELECT no_kk FROM penduduk WHERE kecamatan_id = $k->id GROUP BY no_kk";
            $p = $db->exec('all');
            if($p)
            foreach($p as $_p)
            {
                $survey = $db->single('survey',['tanggal' => ['LIKE','%'.$periode.'%'],'no_kk'=>$_p->no_kk]);
                if($survey && $survey->status == 'publish')
                {
                    $survey->nilai = json_decode($survey->nilai);
                    $survey->kategori = json_decode($survey->kategori);
                    
                    $all_skor = [];
                    foreach($survey->nilai as $nilai): 
                        $all_skor[] = $nilai->skor;
                    endforeach;
                    $nilai = array_count_values($all_skor);
                    $question = array_sum($nilai) - ($nilai['N']??0);
                    if(isset($nilai['N'])) unset($nilai['N']);
                    if($nilai[1] == 0 || $question == 0) continue;
                    $_total_nilai = ($nilai[1] / $question);
                    if(is_nan($_total_nilai)) continue;
                    $total_iks += $_total_nilai;
                    $counter++;
                }
            }

            if($counter)
            {
                $skor = $total_iks/$counter;
                $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
                $k->kategori = $db->exec('single');
                $k->total_skor = $skor;
            }
            else
            {
                $k->total_skor = 0;
            }
            $k->periode = $periode;
            $k->jumlah_kk = count($p);
            $k->kk_nilai = $counter;
            $k->kk_belum_nilai = $k->jumlah_kk - $k->kk_nilai;
            return $k;
        }, $all_kecamatan);

        $db->query = "SELECT no_kk FROM penduduk GROUP BY no_kk";
        $jumlah_kk = $db->exec('exists');

        $iks_kabupaten = (array) $iks;
        $iks_kabupaten = array_sum(array_column($iks_kabupaten,'total_skor'));
        $iks_kabupaten = number_format($iks_kabupaten / $jumlah_kk,3);

        $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kabupaten AND nilai_akhir >= $iks_kabupaten";
        $iks_kabupaten = $db->exec('single');

        // return compact('kecamatan','kelurahan','lingkungan','penduduk','iks','jumlah_kk','iks_kabupaten');
        ob_start();
        require '../templates/rekapitulasi/tpl/index.php';
        $content = ob_get_clean();
        $cached = fopen($cachefile, 'w');
        fwrite($cached, $content);
        fclose($cached);
        return $content;
    }

    function kecamatan()
    {
        $db = $this->db;

        $periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        $kecamatan_id = $_GET['kecamatan_id'];

        $cachefile = 'cached/rekapitulasi/kecamatan-'.$kecamatan_id.'-'.$periode.'.html';
        if (file_exists($cachefile) && !isset($_GET['nocache'])) {
            ob_start();
            readfile($cachefile);
            return ob_get_clean();
        }

        $all_kelurahan = $db->all('kelurahan',['kecamatan_id' => $kecamatan_id]);

        $kel_ids = [];
        foreach($all_kelurahan as $kel)
        {
            $kel_ids[] = $kel->id;
        }

        $all_lingkungan = $db->all('lingkungan',['kelurahan_id'=>['IN','('.implode(',',$kel_ids).')']]);

        $kelurahan  = count($all_kelurahan);
        $lingkungan = count($all_lingkungan);
        $penduduk   = $db->exists('penduduk',['kecamatan_id'=>$kecamatan_id]);

        $iks = array_map(function($k) use ($db, $periode, $penduduk){
            $counter = 0;
            $total_iks = 0;
            $iks_per_indikator = [];
            $db->query = "SELECT no_kk FROM penduduk WHERE kelurahan_id = $k->id GROUP BY no_kk";
            $p = $db->exec('all');
            if($p)
            foreach($p as $_p)
            {
                $survey = $db->single('survey',['tanggal' => ['LIKE','%'.$periode.'%'],'no_kk'=>$_p->no_kk]);
                if($survey && $survey->status == 'publish')
                {
                    
                    $survey->nilai = json_decode($survey->nilai);
                    $survey->kategori = json_decode($survey->kategori);
                    
                    $all_skor = [];
                    foreach($survey->nilai as $index => $nilai): 
                        $iks_per_indikator[$index][] = $nilai->skor;
                        $all_skor[] = $nilai->skor;
                    endforeach;
                    $nilai = array_count_values($all_skor);
                    $question = array_sum($nilai) - ($nilai['N']??0);
                    if(isset($nilai['N'])) unset($nilai['N']);
                    if($nilai[1] == 0 || $question == 0) continue;
                    $_total_nilai = ($nilai[1] / $question);
                    if(is_nan($_total_nilai)) continue;
                    $total_iks += $_total_nilai;
                    $counter++;
                }
            }

            if($counter)
            {
                $skor = $total_iks/count($p);
                $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
                $k->kategori = $db->exec('single');
                $k->total_skor = $skor;
            }
            else
            {
                $k->total_skor = 0;
            }

            foreach($iks_per_indikator as $index => $iks_p_indikator)
            {
                $n = array_count_values($iks_p_indikator);
                $unsurvey = count($p) - $counter;
                $total_1 = $n['1']??0;
                $pembagi = $total_1+($n['0']??0)+$unsurvey;
                if($total_1 == 0 && $pembagi == 0 && ($n['N']??0))
                {
                    $warna = 'blue';
                    $presentase = 'N';
                }
                else if(in_array(0,[$total_1, $pembagi]))
                {
                    $warna = 'red';
                    $presentase = '0';
                }
                else
                {
                    $presentase = number_format( $total_1 /  $pembagi, 3 );
                    $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $presentase AND nilai_akhir >= $presentase";
                    $warna = $db->exec('single')->warna;
                }

                $iks_per_indikator[$index] = [
                    'presentase' => $presentase,
                    'warna' => $warna
                ];
            }
            $k->iks_per_indikator = $iks_per_indikator;
            $k->periode = $periode;
            $k->jumlah_kk = count($p);
            $k->kk_nilai = $counter;
            $k->kk_belum_nilai = $k->jumlah_kk - $k->kk_nilai;
            return $k;
        }, $all_kelurahan);

        $detail_kecamatan = $db->single('kecamatan',['id' => $kecamatan_id]);

        $db->query = "SELECT no_kk FROM penduduk WHERE kecamatan_id = $kecamatan_id GROUP BY no_kk";
        $jumlah_kk = $db->exec('exists');

        $iks_kecamatan = json_decode(json_encode($iks),1);
        $iks_kecamatan = array_sum(array_column($iks_kecamatan,'total_skor'));

        $iks_kecamatan = number_format($iks_kecamatan/$jumlah_kk, 3);
        $skor_iks_kecamatan = $iks_kecamatan;

        $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kecamatan AND nilai_akhir >= $iks_kecamatan";
        $iks_kecamatan = $db->exec('single');

        $indikator = $db->all('indikator');

        // return compact('kelurahan','lingkungan','penduduk','iks','detail_kecamatan','iks_kecamatan','jumlah_kk','indikator','skor_iks_kecamatan','db');

        ob_start();
        require '../templates/rekapitulasi/tpl/kecamatan.php';
        $content = ob_get_clean();
        $cached = fopen($cachefile, 'w');
        fwrite($cached, $content);
        fclose($cached);
        return $content;
    }
    
    function kelurahan()
    {

        $db   = $this->db;

        $kelurahan_id = $_GET['kelurahan_id'];

        $periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        $cachefile = 'cached/rekapitulasi/kelurahan-'.$kelurahan_id.'-'.$periode.'.html';
        if (file_exists($cachefile) && !isset($_GET['nocache'])) {
            ob_start();
            readfile($cachefile);
            return ob_get_clean();
        }

        $all_lingkungan = $db->all('lingkungan',['kelurahan_id'=>$kelurahan_id]);

        $lingkungan = count($all_lingkungan);
        $penduduk = $db->exists('penduduk',['kelurahan_id'=>$kelurahan_id]);


        $iks = array_map(function($k) use ($db, $periode, $penduduk){
            $counter = 0;
            $total_iks = 0;
            $iks_per_indikator = [];
            $db->query = "SELECT no_kk FROM penduduk WHERE lingkungan_id = $k->id GROUP BY no_kk";
            $p = $db->exec('all');
            if($p)
            foreach($p as $_p)
            {
                $survey = $db->single('survey',['tanggal' => ['LIKE','%'.$periode.'%'],'no_kk'=>$_p->no_kk]);
                if($survey && $survey->status == 'publish')
                {
                    $survey->nilai = json_decode($survey->nilai);
                    $survey->kategori = json_decode($survey->kategori);
                    
                    $all_skor = [];
                    foreach($survey->nilai as $index => $nilai): 
                        $iks_per_indikator[$index][] = $nilai->skor;
                        $all_skor[] = $nilai->skor;
                    endforeach;
                    $nilai = array_count_values($all_skor);
                    $question = array_sum($nilai) - ($nilai['N']??0);
                    if(isset($nilai['N'])) unset($nilai['N']);
                    if($nilai[1] == 0 || $question == 0) continue;
                    $_total_nilai = ($nilai[1] / $question);
                    if(is_nan($_total_nilai)) continue;
                    $total_iks += $_total_nilai;
                    $counter++;
                }
            }

            if($counter)
            {
                $skor = $total_iks/count($p);
                $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
                $k->kategori = $db->exec('single');
                $k->total_skor = $skor;
            }
            else
            {
                $k->total_skor = 0;
            }

            foreach($iks_per_indikator as $index => $iks_p_indikator)
            {
                $n = array_count_values($iks_p_indikator);
                $unsurvey = count($p) - $counter;
                $total_1 = $n['1']??0;
                $pembagi = $total_1+($n['0']??0)+$unsurvey;
                if($total_1 == 0 && $pembagi == 0 && ($n['N']??0))
                {
                    $warna = 'blue';
                    $presentase = 'N';
                }
                else if(in_array(0,[$total_1, $pembagi]))
                {
                    $warna = 'red';
                    $presentase = '0';
                }
                else
                {
                    $presentase = number_format( $total_1 /  $pembagi, 3 );
                    $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $presentase AND nilai_akhir >= $presentase";
                    $warna = $db->exec('single')->warna;
                }

                $iks_per_indikator[$index] = [
                    'presentase' => $presentase,
                    'warna' => $warna
                ];
            }
            $k->iks_per_indikator = $iks_per_indikator;
            $k->periode = $periode;
            $k->jumlah_kk = count($p);
            $k->kk_nilai = $counter;
            $k->kk_belum_nilai = $k->jumlah_kk - $k->kk_nilai;
            return $k;
        }, $all_lingkungan);

        $detail_kelurahan = $db->single('kelurahan',['id' => $kelurahan_id]);
        $detail_kelurahan->kecamatan = $db->single('kecamatan',['id' => $detail_kelurahan->kecamatan_id]);

        $db->query = "SELECT no_kk FROM penduduk WHERE kelurahan_id = $kelurahan_id GROUP BY no_kk";
        $jumlah_kk = $db->exec('exists');

        $iks_kelurahan = (array) $iks;

        $iks_kelurahan = array_sum(array_column($iks_kelurahan,'total_skor'));

        $iks_kelurahan = number_format($iks_kelurahan/$jumlah_kk, 3);
        $skor_iks_kelurahan = $iks_kelurahan;

        $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kelurahan AND nilai_akhir >= $iks_kelurahan";
        $iks_kelurahan = $db->exec('single');

        $indikator = $db->all('indikator');

        // return compact('lingkungan','penduduk','iks','detail_kelurahan','jumlah_kk','iks_kelurahan','skor_iks_kelurahan','indikator','db');
        ob_start();
        require '../templates/rekapitulasi/tpl/kelurahan.php';
        $content = ob_get_clean();
        $cached = fopen($cachefile, 'w');
        fwrite($cached, $content);
        fclose($cached);
        return $content;
    }
    
    function lingkungan()
    {
        $db   = $this->db;

        $penduduk = count($db->all('penduduk',['lingkungan_id'=>$_GET['lingkungan_id']]));

        $periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        $cachefile = 'cached/rekapitulasi/lingkungan-'.$_GET['lingkungan_id'].'-'.$periode.'.html';
        if (file_exists($cachefile) && !isset($_GET['nocache'])) {
            ob_start();
            readfile($cachefile);
            return ob_get_clean();
        }

        $db->query = "SELECT no_kk FROM penduduk WHERE lingkungan_id = $_GET[lingkungan_id] GROUP BY no_kk";
        $all_kk = $db->exec('all');
        $jumlah_kk = count($all_kk);
        $iks = [];
        $all_survey = [];
        foreach($all_kk as $k)
        {
            $survey = $db->single('survey',['tanggal' => ['LIKE','%'.$periode.'%'],'no_kk'=>$k->no_kk]);
            if($survey && $survey->status == 'publish')
            {
                $survey->nilai = json_decode($survey->nilai);
                $survey->kategori = json_decode($survey->kategori);
                $all_skor = [];
                foreach($survey->nilai as $nilai): 
                    $all_skor[] = $nilai->skor;
                endforeach;
                $nilai = array_count_values($all_skor);
                $question = array_sum($nilai) - ($nilai['N']??0);
                if(isset($nilai['N'])) unset($nilai['N']);
                if($nilai[1] == 0 || $question == 0) continue;
                $skor = ($nilai[1] / $question);
                if(is_nan($skor)) continue;
                $survey->total_skor = $skor;
                $all_survey[] = $survey;

                $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
                $k->kategori = $db->exec('single');
                $k->periode = explode('-',$periode);
                $k->survey = $survey;
                $k->total_skor = $skor;
                $iks[] = $k;
            }
        }

        $detail_lingkungan = $db->single('lingkungan',['id' => $_GET['lingkungan_id']]);
        $detail_lingkungan->kelurahan = $db->single('kelurahan',['id' => $detail_lingkungan->kelurahan_id]);
        $detail_lingkungan->kecamatan = $db->single('kecamatan',['id' => $detail_lingkungan->kelurahan->kecamatan_id]);

        $iks_lingkungan = (array) $iks;
        $iks_lingkungan = array_sum(array_column($iks_lingkungan,'total_skor'));
        $skor_iks_lingkungan = number_format($iks_lingkungan / $jumlah_kk, 3);

        $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor_iks_lingkungan AND nilai_akhir >= $skor_iks_lingkungan";
        $iks_lingkungan = $db->exec('single');

        $indikator = $db->all('indikator');

        // return compact('penduduk','iks','detail_lingkungan','jumlah_kk','iks_lingkungan','indikator','all_survey','db','skor_iks_lingkungan');

        ob_start();
        require '../templates/rekapitulasi/tpl/lingkungan.php';
        $content = ob_get_clean();
        $cached = fopen($cachefile, 'w');
        fwrite($cached, $content);
        fclose($cached);
        return $content;
    }

}