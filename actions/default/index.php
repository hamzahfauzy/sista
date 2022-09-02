<?php

$conn = conn();
$db   = new Database($conn);

Page::set_title('Dashboard');

$user = auth()->user;

$periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

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
    $p = $db->all('penduduk',['kecamatan_id'=>$k->id]);
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
            $total = 0;
            $skoring = 0;
            foreach($survey->nilai as $nilai): 
                if($nilai->skor===true||$nilai->skor===false)
                {
                    $total += $nilai->skor;
                    $skoring++;
                }
            endforeach;
            $total_iks += ($total/$skoring);
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
}, $all_kecamatan);

$db->query = "SELECT no_kk FROM penduduk GROUP BY no_kk";
$jumlah_kk = $db->exec('exists');

$iks_kabupaten = (array) $iks;
$iks_kabupaten = array_sum(array_column($iks_kabupaten,'total_skor'));

$db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kabupaten AND nilai_akhir >= $iks_kabupaten";
$iks_kabupaten = $db->exec('single');

return compact('kecamatan','kelurahan','lingkungan','penduduk','iks','jumlah_kk','iks_kabupaten');