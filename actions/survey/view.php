<?php

Page::set_title('Detail Survey');

$conn = conn();
$db   = new Database($conn);

$data = $db->single('survey',['id' => $_GET['id']]);
$data->nilai = json_decode($data->nilai);
$data->kategori = json_decode($data->kategori);

$rekap_nilai = [];
$skor_in_count = 0;
$total_skor = 0;
foreach($data->nilai as $nilai)
{
    $indikator = $nilai->indikator;
    $rekap_penduduk = [];
    $jawaban_penduduk = [];
    foreach($nilai->rekap_penduduk as $penduduk)
    {
        $rekap_penduduk[] = [
            'penduduk' => $penduduk->penduduk,
            'jawaban'  => $penduduk->jawaban 
        ];
        $jawaban_penduduk[] = $penduduk->jawaban;
    }

    $jawaban        = $indikator->jawaban;
    $jumlah_jawaban = array_count_values($jawaban_penduduk);
    $question       = array_sum($jumlah_jawaban);
    $non_question   = ($jumlah_jawaban['N']??0) + ($jumlah_jawaban['disable']??0);
    $question       = $question - $non_question;
    if($question > 0)
    {
        $skor = $indikator->logika == 'and' && isset($jumlah_jawaban[$jawaban]) ? $question == $jumlah_jawaban[$jawaban] : $jumlah_jawaban[$jawaban] > 0;
        $skor = (int) $skor;

        $skor_in_count++;
        $total_skor+=$skor;
    }
    else
    {
        $skor = 'N';
    }

    $rekap_nilai[] = [
        'indikator' => $indikator,
        'rekap_penduduk'  => $rekap_penduduk,
        'skor' => $skor
    ];
}

$skor = in_array(0,[$total_skor,$skor_in_count]) ? 0 : $total_skor / $skor_in_count;
$db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
$status = $db->exec('single');

$data = $db->update('survey',[
    'nilai' => json_encode($rekap_nilai),
    'kategori' => json_encode($status),
],['id' => $_GET['id']]);

$data->nilai = json_decode($data->nilai);
$data->kategori = json_decode($data->kategori);

return compact('data');