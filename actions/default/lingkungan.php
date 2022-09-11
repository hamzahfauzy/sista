<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

$penduduk = count($db->all('penduduk',['lingkungan_id'=>$_GET['lingkungan_id']]));

$periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

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
        $skor = ($nilai[1] / $question);
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

return compact('penduduk','iks','detail_lingkungan','jumlah_kk','iks_lingkungan','indikator','all_survey','db','skor_iks_lingkungan');