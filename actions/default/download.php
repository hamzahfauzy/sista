<?php
// reference the Dompdf namespace
use Dompdf\Dompdf;

if(!isset($_GET['nik']) || $_GET['nik'] != auth()->user->username)
{
    header('location:'.routeTo('default/landing'));
    die();
}

$conn = conn();
$db   = new Database($conn);

$data = $db->single('survey',['no_kk' => $_GET['id']]);

$data->nilai = json_decode($data->nilai);
$data->kategori = json_decode($data->kategori);

$rekap_nilai = [];
$skor_in_count = 0;
$total_skor = 0;
foreach($data->nilai as $nilai)
{
    $indikator = $db->single('indikator',['id' => $nilai->indikator->id]);
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
        $skor = $indikator->logika == 'and' && isset($jumlah_jawaban[$jawaban]) ? $question == $jumlah_jawaban[$jawaban] : (isset($jumlah_jawaban[$jawaban]) ? 1 : 0);
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
],['no_kk' => $_GET['id']]);

$data->nilai = json_decode($data->nilai);
$data->kategori = json_decode($data->kategori);

ob_start();
require '../templates/default/download.php';
$html = ob_get_clean();

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("indeks-keluarga-sehat-$data->no_kk");

// die();