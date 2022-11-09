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
$penduduk->kecamatan = $db->single('kecamatan',['id'=>$penduduk->kecamatan_id]);
$penduduk->kelurahan = $db->single('kelurahan',['id'=>$penduduk->kelurahan_id]);
$penduduk->lingkungan = $db->single('lingkungan',['id'=>$penduduk->lingkungan_id]);
$penduduk->keluarga = $db->all('penduduk',['no_kk'=>$penduduk->no_kk]);

$indikator = false;
$indikator_tambahan = false;

$data = $db->all('survey',[
    'status' => ['<>','draft'],
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

$kecamatan = $db->all('kecamatan');
$content = "";

if(isset($_GET['tampil']))
{
    if(isset($_GET['kecamatan_id']) && $_GET['kecamatan_id'] != '*')
    {
        if(isset($_GET['kelurahan_id']) && $_GET['kelurahan_id'] != '*')
        {
            if(isset($_GET['lingkungan_id']) && $_GET['lingkungan_id'] != '*')
            {
                $content = (new Rekap)->lingkungan();
                return compact('kecamatan','content','data','penduduk','nik');
            }

            $content = (new Rekap)->kelurahan();
            return compact('kecamatan','content','data','penduduk','nik');
        }

        $content = (new Rekap)->kecamatan();
        return compact('kecamatan','content','data','penduduk','nik');
    }

    $content = (new Rekap)->index();
    return compact('kecamatan','content','data','penduduk','nik');
}


if(isset($_GET['page']))
{
    if($_GET['page'] == 'form-survey')
    {
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

                
            $insert = $db->insert('survey',[
                'user_id' => auth()->user->id,
                'no_kk' => $penduduk->no_kk,
                'nilai' => json_encode($rekap_nilai),
                'tanggal' => date('Y-m-d'),
                'berkas' => '',
                'kategori' => json_encode($status),
                'status' => 'mandiri',
                'indikator_tambahan' => json_encode($_POST['indikator_tambahan'])
            ]);

            set_flash_msg(['success'=>'Survey berhasil disimpan']);
            header('location:'.routeTo('default/riwayat',['nik'=>$_GET['nik']]));
            die();
        }
        $indikator = $db->all('indikator',[],['no_urut'=>'asc']);
        $indikator_tambahan = $db->all('indikator_tambahan');
    }
}

return compact('kecamatan','content','data','penduduk','nik','indikator','indikator_tambahan');