<?php

Page::set_title('Edit Survey');

$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');

$conn = conn();
$db   = new Database($conn);

$data = $db->single('survey',['id' => $_GET['id']]);
$data->nilai = json_decode($data->nilai);
$data->kategori = json_decode($data->kategori);

if(request() == 'POST')
{
    $rekap_nilai = [];
    $skor_in_count = 0;
    $total_skor = 0;
    foreach($_POST['pengaturan'] as $indikator_id => $nilai)
    {
        $indikator = $db->single('indikator',['id' => $indikator_id]);
        $rekap_penduduk = [];
        $jawaban_penduduk = [];
        foreach($nilai as $penduduk_id => $jawaban)
        {
            $penduduk = $db->single('penduduk',['id' => $penduduk_id]);
            $rekap_penduduk[] = [
                'penduduk' => $penduduk,
                'jawaban'  => $jawaban 
            ];
            $jawaban_penduduk[] = $jawaban;
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

        
    $db->update('survey',[
        'nilai' => json_encode($rekap_nilai),
        'kategori' => json_encode($status),
    ],[
        'id' => $_GET['id']
    ]);


    set_flash_msg(['success'=>'Survey berhasil update']);
    header('location:'.routeTo('survey/view',['id' => $_GET['id']]));
}

return [
    'data' => $data,
    'error_msg' => $error_msg,
    'old' => $old,
];