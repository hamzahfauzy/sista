<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

$kelurahan_id = $_GET['kelurahan_id'];

if(get_role($user->id)->name == 'admin kelurahan')
{
    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kelurahan_id = $petugas->kelurahan_id;
}

$all_lingkungan = $db->all('lingkungan',['kelurahan_id'=>$kelurahan_id]);

$lingkungan = count($all_lingkungan);
$penduduk = $db->exists('penduduk',['kelurahan_id'=>$kelurahan_id]);

$periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

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
            $counter++;
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
            $total_iks += ($nilai[1] / $question);
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

return compact('lingkungan','penduduk','iks','detail_kelurahan','jumlah_kk','iks_kelurahan','skor_iks_kelurahan','indikator','db');