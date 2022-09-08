<?php

$table = 'survey';
Page::set_title('Tambah '.ucwords($table));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];

$conn = conn();
$db   = new Database($conn);

if(file_exists('../actions/'.$table.'/override-create-fields.php'))
    $fields = require '../actions/'.$table.'/override-create-fields.php';

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

        $jawaban = $indikator->jawaban;
        $jumlah_jawaban = array_count_values($jawaban_penduduk);
        $skor = isset($jumlah_jawaban['N']) && $jumlah_jawaban['N'] == count($nilai) ? 'N' : 0;

        if($skor != 'N')
        {
            $c_nilai = count($nilai) - (isset($jumlah_jawaban['N']) ? $jumlah_jawaban['N'] : 0);
            $skor = $indikator->logika == 'and' && isset($jumlah_jawaban[$jawaban]) ? $c_nilai == $jumlah_jawaban[$jawaban] : in_array($jawaban,$jawaban_penduduk);

            $skor_in_count++;
            $total_skor+=$skor;
        }

        $rekap_nilai[] = [
            'indikator' => $indikator,
            'rekap_penduduk'  => $rekap_penduduk,
            'skor' => $skor
        ];
    }

    // echo json_encode($rekap_nilai);
    // die();

    $skor = in_array(0,[$total_skor,$skor_in_count]) ? 0 : $total_skor / $skor_in_count;
    $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
    $status = $db->exec('single');

    if(isset($_FILES['berkas']) && !empty($_FILES['berkas']['name']))
    {
        $ext  = pathinfo($_FILES['berkas']['name'], PATHINFO_EXTENSION);
        $name = strtotime('now').'.'.$ext;
        $file = 'uploads/'.$name;
        copy($_FILES['berkas']['tmp_name'],$file);
        
        $db->insert('survey',[
            'user_id' => auth()->user->id,
            'no_kk' => $_GET['no_kk'],
            'nilai' => json_encode($rekap_nilai),
            'tanggal' => $_GET['tanggal'],
            'berkas' => $file,
            'kategori' => json_encode($status),
            'status' => 'draft'
        ]);
    }


    set_flash_msg(['success'=>'Survey berhasil disimpan']);
    header('location:'.routeTo('survey/index'));
}

$data      = false;
$keluarga  = false;
$indikator = false;

if(isset($_GET['filter']))
{
    $id_keluarga = strtotime('now') + mt_rand(100,1000);
    if(isset($_GET['nik_ayah']) && !empty($_GET['nik_ayah']))
    {
        $db->update('penduduk',['no_kk'=>$id_keluarga,'sebagai'=>'Ayah'],['NIK'=>$_GET['nik_ayah']]);
    }
    if(isset($_GET['nik_ibu']) && !empty($_GET['nik_ibu']))
    {
        $db->update('penduduk',['no_kk'=>$id_keluarga,'sebagai'=>'Ibu'],['NIK'=>$_GET['nik_ibu']]);
    }
    if(isset($_GET['nik_anak']) && !empty($_GET['nik_anak']))
    {
        $NIKs = explode(',',$_GET['nik_anak']);
        foreach($NIKs as $nik)
            $db->update('penduduk',['no_kk'=>$id_keluarga,'sebagai'=>'Anak'],['NIK'=>$nik]);
    }

    header('location:'.routeTo('survey/create',['no_kk'=>$id_keluarga,'tanggal'=>$_GET['tanggal']]));
    die();

}
if(isset($_GET['no_kk']))
{
    $keluarga = $db->all('penduduk',['no_kk' => $_GET['no_kk']]);
    if($keluarga)
    {
        $periode = date('Y-m', strtotime($_GET['tanggal']));
        $data = $db->single('survey',['no_kk'=>$_GET['no_kk'],'tanggal'=>['LIKE','%'.$periode.'%']]);
        if(empty($data))
        {
            $indikator = $db->all('indikator',[],['no_urut'=>'asc']);
        }
    }
}

$pengaturan = ['ayah','ibu','anak >5 tahun','anak balita','anak bayi'];

return compact('table','error_msg','old','fields','keluarga','data','pengaturan','indikator');