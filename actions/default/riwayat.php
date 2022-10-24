<?php

if(!isset($_GET['nik']) || $_GET['nik'] != auth()->user->username)
{
    header('location:'.routeTo('default/landing'));
    die();
}

$conn = conn();
$db   = new Database($conn);

$nik  = $_GET['nik'];

$penduduk = $db->single('penduduk',['NIK'=>$nik]);

$data = $db->all('survey',[
    'status' => 'publish',
    'nilai'  => ['LIKE','%'.$nik.'%']
]);

foreach($data as $survey)
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

    $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
    $survey->kategori = $db->exec('single');
    $survey->total_skor = $skor;
}

if(empty($data))
{
    header('location:'.routeTo('default/landing'));
    die();
}

return compact('data','penduduk');