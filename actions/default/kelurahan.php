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
}, $all_lingkungan);

$detail_kelurahan = $db->single('kelurahan',['id' => $kelurahan_id]);
$detail_kelurahan->kecamatan = $db->single('kecamatan',['id' => $detail_kelurahan->kecamatan_id]);

$db->query = "SELECT no_kk FROM penduduk WHERE kelurahan_id = $kelurahan_id GROUP BY no_kk";
$jumlah_kk = $db->exec('exists');

$iks_kelurahan = (array) $iks;
$iks_kelurahan = array_sum(array_column($iks_kelurahan,'total_skor'));

$db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kelurahan AND nilai_akhir >= $iks_kelurahan";
$iks_kelurahan = $db->exec('single');

return compact('lingkungan','penduduk','iks','detail_kelurahan','jumlah_kk','iks_kelurahan');