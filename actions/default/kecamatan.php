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

$iks = array_map(function($k) use ($db, $periode){
    $p = $db->all('penduduk',['kelurahan_id'=>$k->id]);
    $counter = 0;
    $total_iks = 0;
    foreach($p as $_p)
    {
        $survey = $db->single('survey',['tanggal' => ['LIKE','%'.$periode.'%'],'no_kk'=>$_p->no_kk]);
        if($survey && $survey->status == 'publish')
        {
            $counter++;
            $survey->nilai = json_decode($survey->nilai);
            $survey->kategori = json_decode($survey->kategori);
            
            $all_skor = [];
            foreach($survey->nilai as $nilai): 
                $all_skor[] = $nilai->skor;
            endforeach;
            $nilai = array_count_values($all_skor);
            $question = array_sum($nilai) - ($nilai['N']??0);
            if(isset($nilai['N'])) unset($nilai['N']);
            $total_iks += (($nilai[1] - $nilai[0]) / $question);
        }
    }

    if($counter)
    {
        $skor = $total_iks/$counter;
        $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
        $k->kategori = $db->exec('single');
        $k->total_skor = $skor;
    }
    $k->periode = $periode;
    return $k;
}, $all_kelurahan);

$detail_kecamatan = $db->single('kecamatan',['id' => $kecamatan_id]);

$db->query = "SELECT no_kk FROM penduduk WHERE kecamatan_id = $kecamatan_id GROUP BY no_kk";
$jumlah_kk = $db->exec('exists');

$iks_kecamatan = (array) $iks;
$iks_kecamatan = array_sum(array_column($iks_kecamatan,'total_skor'));

$db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kecamatan AND nilai_akhir >= $iks_kecamatan";
$iks_kecamatan = $db->exec('single');

return compact('kelurahan','lingkungan','penduduk','iks','detail_kecamatan','iks_kecamatan','jumlah_kk');