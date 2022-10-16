<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

$periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$kecamatan_id = $_GET['kecamatan_id'];

if(!in_array(get_role($user->id)->name,['administrator','bupati']))
{
    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kecamatan_id = $petugas->kecamatan_id;
    if(!empty($petugas->kelurahan_id))
    {
        header('location:'.routeTo('default/kelurahan',[
            'kelurahan_id' => $petugas->kelurahan_id,
            'tahun' => $periode,
        ]));
        die();
    }
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

return compact('kelurahan','lingkungan','penduduk','iks','detail_kecamatan','iks_kecamatan','jumlah_kk','indikator','skor_iks_kecamatan','db');