<?php

$conn = conn();
$db   = new Database($conn);

$user = auth()->user;

$periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

if(!in_array(get_role($user->id)->name,['administrator','pembina kabupaten','bupati']))
{
    $petugas = $db->single('petugas',['user_id'=>$user->id]);
    $kecamatan_id = $petugas->kecamatan_id;
    if(!empty($petugas->kelurahan_id))
    {
        header('location:'.routeTo('default/kelurahan',[
            'kelurahan_id' => $petugas->kelurahan_id,
            'tahun' => $periode,
        ]));
    }
    header('location:'.routeTo('default/kecamatan',[
        'kecamatan_id' => $kecamatan_id,
        'tahun' => $periode,
    ]));
    die();
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
            $total_iks += ($nilai[1] / $question);
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
    return $k;
}, $all_kecamatan);

$db->query = "SELECT no_kk FROM penduduk GROUP BY no_kk";
$jumlah_kk = $db->exec('exists');

$iks_kabupaten = (array) $iks;
$iks_kabupaten = array_sum(array_column($iks_kabupaten,'total_skor'));
$iks_kabupaten = number_format($iks_kabupaten / $jumlah_kk,3);

$db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kabupaten AND nilai_akhir >= $iks_kabupaten";
$iks_kabupaten = $db->exec('single');

return compact('kecamatan','kelurahan','lingkungan','penduduk','iks','jumlah_kk','iks_kabupaten');