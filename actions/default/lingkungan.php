<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

if(!in_array(get_role($user->id)->name,['administrator','bupati']))
{

}

$penduduk = count($db->all('penduduk',['lingkungan_id'=>$_GET['lingkungan_id']]));

$periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$db->query = "SELECT no_kk FROM penduduk WHERE lingkungan_id = $_GET[lingkungan_id] GROUP BY no_kk";
$all_kk = $db->exec('all');
$jumlah_kk = count($all_kk);
$iks = [];
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
        $skor = (($nilai[1] - $nilai[0]) / $question);

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

$db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_lingkungan AND nilai_akhir >= $iks_lingkungan";
$iks_lingkungan = $db->exec('single');

return compact('penduduk','iks','detail_lingkungan','jumlah_kk','iks_lingkungan');