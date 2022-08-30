<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

if(!in_array(get_role($user->id)->name,['administrator','bupati']))
{

}

$all_kecamatan = $db->all('kecamatan');
$all_kelurahan = $db->all('kelurahan');
$all_lingkungan = $db->all('lingkungan');

$kecamatan  = count($all_kecamatan);
$kelurahan  = count($all_kelurahan);
$lingkungan = count($all_lingkungan);
$penduduk = count($db->all('penduduk'));

$periode = isset($_GET['bulan']) && isset($_GET['tahun']) ? $_GET['tahun'] .'-'. ($_GET['bulan'] < 10 ? "0".$_GET['bulan'] : $_GET['bulan']) : date('Y-m');

$db->query = "SELECT no_kk FROM penduduk WHERE lingkungan_id = $_GET[lingkungan_id] GROUP BY no_kk";
$all_kk = $db->exec('all');
$iks = [];
foreach($all_kk as $k)
{
    $survey = $db->single('survey',['tanggal' => ['LIKE','%'.$periode.'%'],'no_kk'=>$k->no_kk]);
    if($survey && $survey->status == 'publish')
    {
        $survey->nilai = json_decode($survey->nilai);
        $survey->kategori = json_decode($survey->kategori);
        $total = 0;
        $skoring = 0;
        foreach($survey->nilai as $nilai): 
            if($nilai->skor===true||$nilai->skor===false)
            {
                $total += $nilai->skor;
                $skoring++;
            }
        endforeach;
        $skor = ($total/$skoring);
        $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
        $k->kategori = $db->exec('single');
        $k->periode = explode('-',$periode);
        $k->survey = $survey;
        $iks[] = $k;
    }
}

$detail_lingkungan = $db->single('lingkungan',['id' => $_GET['lingkungan_id']]);
$detail_lingkungan->kelurahan = $db->single('kelurahan',['id' => $detail_lingkungan->kelurahan_id]);
$detail_lingkungan->kecamatan = $db->single('kecamatan',['id' => $detail_lingkungan->kelurahan->kecamatan_id]);

return compact('kecamatan','kelurahan','lingkungan','penduduk','iks','detail_lingkungan');