<?php

// check if file exists
$parent_path = '';
if (!in_array(php_sapi_name(),["cli","cgi-fcgi"])) {
    $parent_path = 'public/';
}

if(file_exists($parent_path . 'lock.txt'))
{
    die();
}

file_put_contents($parent_path . 'lock.txt', strtotime('now'));

echo "Counting Start\n";

$conn = conn();
$db   = new Database($conn);

$datas = $db->all('survey');
foreach($datas as $data)
{
    echo "Counting $data->no_kk Started\n";
    $data->nilai = json_decode($data->nilai);
    $data->kategori = json_decode($data->kategori);
    
    $rekap_nilai = [];
    $_penduduk = [];
    $skor_in_count = 0;
    $total_skor = 0;
    foreach($data->nilai as $nilai)
    {
        $indikator = $db->single('indikator',['id' => $nilai->indikator->id]);
        $rekap_penduduk = [];
        $jawaban_penduduk = [];
        foreach($nilai->rekap_penduduk as $penduduk)
        {
            $_penduduk = $penduduk->penduduk;
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
    
        $iks_indikator = $db->single('iks_indikator',[
            'tahun'=>date('Y',strtotime($data->tanggal)),
            'no_kk' => $data->no_kk,
            'indikator_id' => $nilai->indikator->id
        ]);
        if($iks_indikator)
        {
            $db->update('iks_indikator',[
                'skor' => $skor,
                'status' => $data->status,
            ],[
                'tahun'=>date('Y',strtotime($data->tanggal)),
                'no_kk' => $data->no_kk,
                'indikator_id' => $nilai->indikator->id
            ]);
        }
        else
        {
            $db->insert('iks_indikator',[
                'tahun' => date('Y',strtotime($data->tanggal)),
                'no_kk' => $data->no_kk,
                'indikator_id' => $nilai->indikator->id,
                'kecamatan_id' => $_penduduk->kecamatan_id,
                'kelurahan_id' => $_penduduk->kelurahan_id,
                'lingkungan_id' => $_penduduk->lingkungan_id,
                'skor' => $skor,
                'status' => $data->status,
            ]);
        }
    
        $rekap_nilai[] = [
            'indikator' => $indikator,
            'rekap_penduduk'  => $rekap_penduduk,
            'skor' => $skor
        ];
    }
    
    $skor = in_array(0,[$total_skor,$skor_in_count]) ? 0 : $total_skor / $skor_in_count;
    $skor = number_format($skor, 3);
    $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
    $status = $db->exec('single');
    
    $data = $db->update('survey',[
        'nilai' => json_encode($rekap_nilai),
        'kategori' => json_encode($status),
    ],['id' => $data->id]);
    
    $data->nilai = json_decode($data->nilai);
    $data->kategori = json_decode($data->kategori);
    
    $iks_penduduk = $db->single('iks_penduduk',[
        'tahun'=>date('Y',strtotime($data->tanggal)),
        'no_kk' => $data->no_kk
    ]);
    if($iks_penduduk)
    {
        $db->update('iks_penduduk',[
            'skor' => $skor,
            'status' => $data->status,
        ],[
            'tahun'=>date('Y',strtotime($data->tanggal)),
            'no_kk' => $data->no_kk
        ]);
    }
    else
    {
        $db->insert('iks_penduduk',[
            'tahun' => date('Y',strtotime($data->tanggal)),
            'no_kk' => $data->no_kk,
            'kecamatan_id' => $_penduduk->kecamatan_id,
            'kelurahan_id' => $_penduduk->kelurahan_id,
            'lingkungan_id' => $_penduduk->lingkungan_id,
            'skor' => $skor,
            'status' => $data->status,
        ]);
    }

    echo "Counting $data->no_kk Finished\n";
}

echo "Counting Finish\n";

unlink($parent_path . 'lock.txt');

die();