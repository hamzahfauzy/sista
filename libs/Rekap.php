<?php

class Rekap {

    function __construct()
    {
        $conn = conn();
        $this->db   = new Database($conn);
    }

    function index()
    {
        $db = $this->db;
        $periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        $iks = [];
        $kecamatan = $db->all('kecamatan');
        foreach($kecamatan as $kec)
        {
            $iks_kec = (new Iks)->byKecamatan($periode, $kec->id);
            if($iks_kec == nan) $iks_kec = 0;
            $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kec AND nilai_akhir >= $iks_kec";
            $iks_kategori = $db->exec('single');

            $jumlah_kk = $db->exists('penduduk',['kecamatan_id'=>$kec->id]);
            $kk_nilai = $db->exists('iks_penduduk',['status'=>'publish','tahun'=>$periode,'kecamatan_id'=>$kec->id]);

            $data_iks = [
                'nama' => $kec->nama,
                'jumlah_kk' => number_format($jumlah_kk),
                'kk_nilai' => number_format($kk_nilai),
                'kk_belum_nilai' => number_format($jumlah_kk-$kk_nilai),
                'kategori' => $iks_kategori
            ];

            $iks[] = json_decode(json_encode($data_iks));
        }

        ob_start();
        require '../templates/rekapitulasi/tpl/index.php';
        $content = ob_get_clean();
        return $content;
    }

    function kecamatan()
    {
        $db = $this->db;

        $periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        $kecamatan_id = $_GET['kecamatan_id'];

        $cachefile = 'cached/rekapitulasi/kecamatan-'.$kecamatan_id.'-'.$periode.'.html';
        if (file_exists($cachefile) && !isset($_GET['nocache'])) {
            ob_start();
            readfile($cachefile);
            return ob_get_clean();
        }

        $all_kelurahan = $db->all('kelurahan',['kecamatan_id' => $kecamatan_id]);

        $kel_ids = [];
        foreach($all_kelurahan as $kel)
        {
            $kel_ids[] = $kel->id;
        }

        $all_lingkungan = $db->all('lingkungan',['kelurahan_id'=>['IN','('.implode(',',$kel_ids).')']]);

        $kelurahan  = count($all_kelurahan);
        $lingkungan = count($all_lingkungan);
        $penduduk   = $db->exists('penduduk',['kecamatan_id'=>$kecamatan_id]);

        $iks = array_map(function($k) use ($db, $periode, $penduduk){
            $counter = 0;
            $total_iks = 0;
            $iks_per_indikator = [];
            $db->query = "SELECT no_kk FROM penduduk WHERE kelurahan_id = $k->id GROUP BY no_kk";
            $p = $db->exec('all');
            if($p)
            foreach($p as $_p)
            {
                $survey = $db->single('survey',['tanggal' => ['LIKE','%'.$periode.'%'],'no_kk'=>$_p->no_kk]);
                if($survey && $survey->status == 'publish')
                {
                    
                    $survey->nilai = json_decode($survey->nilai);
                    $survey->kategori = json_decode($survey->kategori);
                    
                    $all_skor = [];
                    foreach($survey->nilai as $index => $nilai): 
                        $iks_per_indikator[$index][] = $nilai->skor;
                        $all_skor[] = $nilai->skor;
                    endforeach;
                    $nilai = array_count_values($all_skor);
                    $question = array_sum($nilai) - ($nilai['N']??0);
                    if(isset($nilai['N'])) unset($nilai['N']);
                    if($nilai[1] == 0 && $nilai[0] == 0)
                    {
                        $_total_nilai = 1;
                    }
                    else
                    {
                        $_total_nilai = ($nilai[1] / $question);
                        if($_total_nilai == nan) continue;
                    }
                    $total_iks += $_total_nilai;
                    $counter++;
                }
            }

            if($counter)
            {
                $skor = number_format($total_iks/count($p),3);
                $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
                $k->kategori = $db->exec('single');
                $k->total_skor = $skor;
            }
            else
            {
                $k->total_skor = 0;
            }

            foreach($iks_per_indikator as $index => $iks_p_indikator)
            {
                $n = array_count_values($iks_p_indikator);
                $unsurvey = count($p) - $counter;
                $total_1 = $n['1']??0;
                $pembagi = $total_1+($n['0']??0)+$unsurvey;
                if($total_1 == 0 && $pembagi == 0 && ($n['N']??0))
                {
                    $warna = 'blue';
                    $presentase = 'N';
                }
                else if(in_array(0,[$total_1, $pembagi]))
                {
                    $warna = 'red';
                    $presentase = '0';
                }
                else
                {
                    $presentase = number_format( $total_1 /  $pembagi, 3 );
                    $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $presentase AND nilai_akhir >= $presentase";
                    $warna = $db->exec('single')->warna;
                }

                $iks_per_indikator[$index] = [
                    'presentase' => $presentase,
                    'warna' => $warna
                ];
            }
            $k->iks_per_indikator = $iks_per_indikator;
            $k->periode = $periode;
            $k->jumlah_kk = count($p);
            $k->kk_nilai = $counter;
            $k->kk_belum_nilai = $k->jumlah_kk - $k->kk_nilai;
            return $k;
        }, $all_kelurahan);

        $detail_kecamatan = $db->single('kecamatan',['id' => $kecamatan_id]);

        $db->query = "SELECT no_kk FROM penduduk WHERE kecamatan_id = $kecamatan_id GROUP BY no_kk";
        $jumlah_kk = $db->exec('exists');

        $iks_kecamatan = json_decode(json_encode($iks),1);
        $iks_kecamatan = array_sum(array_column($iks_kecamatan,'total_skor'));

        $iks_kecamatan = number_format($iks_kecamatan/$kelurahan, 3);
        $skor_iks_kecamatan = $iks_kecamatan;

        $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kecamatan AND nilai_akhir >= $iks_kecamatan";
        $iks_kecamatan = $db->exec('single');

        $indikator = $db->all('indikator');

        // return compact('kelurahan','lingkungan','penduduk','iks','detail_kecamatan','iks_kecamatan','jumlah_kk','indikator','skor_iks_kecamatan','db');

        ob_start();
        require '../templates/rekapitulasi/tpl/kecamatan.php';
        $content = ob_get_clean();
        $cached = fopen($cachefile, 'w');
        fwrite($cached, $content);
        fclose($cached);
        return $content;
    }
    
    function kelurahan()
    {

        $db   = $this->db;

        $kelurahan_id = $_GET['kelurahan_id'];

        $periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        $cachefile = 'cached/rekapitulasi/kelurahan-'.$kelurahan_id.'-'.$periode.'.html';
        if (file_exists($cachefile) && !isset($_GET['nocache'])) {
            ob_start();
            readfile($cachefile);
            return ob_get_clean();
        }

        $all_lingkungan = $db->all('lingkungan',['kelurahan_id'=>$kelurahan_id]);

        $lingkungan = count($all_lingkungan);
        $penduduk = $db->exists('penduduk',['kelurahan_id'=>$kelurahan_id]);


        $iks = array_map(function($k) use ($db, $periode, $penduduk){
            $counter = 0;
            $total_iks = 0;
            $iks_per_indikator = [];
            $db->query = "SELECT no_kk FROM penduduk WHERE lingkungan_id = $k->id GROUP BY no_kk";
            $p = $db->exec('all');
            if($p)
            foreach($p as $_p)
            {
                $survey = $db->single('survey',['tanggal' => ['LIKE','%'.$periode.'%'],'no_kk'=>$_p->no_kk]);
                if($survey && $survey->status == 'publish')
                {
                    $survey->nilai = json_decode($survey->nilai);
                    $survey->kategori = json_decode($survey->kategori);
                    
                    $all_skor = [];
                    foreach($survey->nilai as $index => $nilai): 
                        $iks_per_indikator[$index][] = $nilai->skor;
                        $all_skor[] = $nilai->skor;
                    endforeach;
                    $nilai = array_count_values($all_skor);
                    $question = array_sum($nilai) - ($nilai['N']??0);
                    if(isset($nilai['N'])) unset($nilai['N']);
                    if($nilai[1] == 0 && $nilai[0] == 0)
                    {
                        $_total_nilai = 1;
                    }
                    else
                    {
                        $_total_nilai = ($nilai[1] / $question);
                        if($_total_nilai == nan) continue;
                    }
                    $total_iks += $_total_nilai;
                    $counter++;
                }
            }

            if($counter)
            {
                $skor = number_format($total_iks/count($p),3);
                $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
                $k->kategori = $db->exec('single');
                $k->total_skor = $skor;
            }
            else
            {
                $k->total_skor = 0;
            }

            foreach($iks_per_indikator as $index => $iks_p_indikator)
            {
                $n = array_count_values($iks_p_indikator);
                $unsurvey = count($p) - $counter;
                $total_1 = $n['1']??0;
                $pembagi = $total_1+($n['0']??0)+$unsurvey;
                if($total_1 == 0 && $pembagi == 0 && ($n['N']??0))
                {
                    $warna = 'blue';
                    $presentase = 'N';
                }
                else if(in_array(0,[$total_1, $pembagi]))
                {
                    $warna = 'red';
                    $presentase = '0';
                }
                else
                {
                    $presentase = number_format( $total_1 /  $pembagi, 3 );
                    $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $presentase AND nilai_akhir >= $presentase";
                    $warna = $db->exec('single')->warna;
                }

                $iks_per_indikator[$index] = [
                    'presentase' => $presentase,
                    'warna' => $warna
                ];
            }
            $k->iks_per_indikator = $iks_per_indikator;
            $k->periode = $periode;
            $k->jumlah_kk = count($p);
            $k->kk_nilai = $counter;
            $k->kk_belum_nilai = $k->jumlah_kk - $k->kk_nilai;
            return $k;
        }, $all_lingkungan);

        $detail_kelurahan = $db->single('kelurahan',['id' => $kelurahan_id]);
        $detail_kelurahan->kecamatan = $db->single('kecamatan',['id' => $detail_kelurahan->kecamatan_id]);

        $db->query = "SELECT no_kk FROM penduduk WHERE kelurahan_id = $kelurahan_id GROUP BY no_kk";
        $jumlah_kk = $db->exec('exists');

        $iks_kelurahan = (array) $iks;

        $iks_kelurahan = array_sum(array_column($iks_kelurahan,'total_skor'));

        $iks_kelurahan = number_format(($iks_kelurahan/$lingkungan)*100, 3);
        $skor_iks_kelurahan = $iks_kelurahan;

        $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $iks_kelurahan AND nilai_akhir >= $iks_kelurahan";
        $iks_kelurahan = $db->exec('single');

        $indikator = $db->all('indikator');

        // return compact('lingkungan','penduduk','iks','detail_kelurahan','jumlah_kk','iks_kelurahan','skor_iks_kelurahan','indikator','db');
        ob_start();
        require '../templates/rekapitulasi/tpl/kelurahan.php';
        $content = ob_get_clean();
        $cached = fopen($cachefile, 'w');
        fwrite($cached, $content);
        fclose($cached);
        return $content;
    }
    
    function lingkungan()
    {
        $db   = $this->db;

        $penduduk = count($db->all('penduduk',['lingkungan_id'=>$_GET['lingkungan_id']]));

        $periode = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        $cachefile = 'cached/rekapitulasi/lingkungan-'.$_GET['lingkungan_id'].'-'.$periode.'.html';
        if (file_exists($cachefile) && !isset($_GET['nocache'])) {
            ob_start();
            readfile($cachefile);
            return ob_get_clean();
        }

        $db->query = "SELECT no_kk FROM penduduk WHERE lingkungan_id = $_GET[lingkungan_id] GROUP BY no_kk";
        $all_kk = $db->exec('all');
        $jumlah_kk = count($all_kk);
        $iks = [];
        $all_survey = [];
        foreach($all_kk as $k)
        {
            $survey = $db->single('survey',['tanggal' => ['LIKE','%'.$periode.'%'],'no_kk'=>$k->no_kk]);
            if($survey && $survey->status == 'publish')
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
                if($nilai[1] == 0&& $nilai[0] == 0)
                {
                    $skor = 1;
                }
                else
                {
                    $skor = ($nilai[1] / $question);
                    if(is_nan($skor)) continue;
                }
                $survey->total_skor = $skor;
                $all_survey[] = $survey;

                $skor = number_format($skor,3);

                $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor AND nilai_akhir >= $skor";
                $k->kategori = $db->exec('single');
                $k->periode = explode('-',$periode);
                $k->survey = $survey;
                $k->total_skor = $skor;
                $iks[] = $k;
            }
        }

        $detail_lingkungan = $db->single('lingkungan',['id' => $_GET['lingkungan_id']]);
        $detail_lingkungan->kelurahan = $db->single('kelurahan',['id' => $detail_lingkungan->kelurahan_id]);
        $detail_lingkungan->kecamatan = $db->single('kecamatan',['id' => $detail_lingkungan->kelurahan->kecamatan_id]);

        $iks_lingkungan = (array) $iks;
        $iks_lingkungan = array_sum(array_column($iks_lingkungan,'total_skor'));
        $skor_iks_lingkungan = number_format($iks_lingkungan / $jumlah_kk, 3);

        $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $skor_iks_lingkungan AND nilai_akhir >= $skor_iks_lingkungan";
        $iks_lingkungan = $db->exec('single');

        $indikator = $db->all('indikator');

        // return compact('penduduk','iks','detail_lingkungan','jumlah_kk','iks_lingkungan','indikator','all_survey','db','skor_iks_lingkungan');

        ob_start();
        require '../templates/rekapitulasi/tpl/lingkungan.php';
        $content = ob_get_clean();
        $cached = fopen($cachefile, 'w');
        fwrite($cached, $content);
        fclose($cached);
        return $content;
    }

    public function kasus($tahun, $type = false, $id = false)
    {
        $cachefile = 'cached/rekapitulasi/kasus'.($type?'-'.$type:'').($id?'-'.$id:'').'-'.$tahun.'.html';
        if (file_exists($cachefile) && !isset($_GET['nocache'])) {
            ob_start();
            readfile($cachefile);
            return ob_get_clean();
        }

        $db = $this->db;
        $indikator_ids = [
            6 => 'Kasus Penderita TBC',
            7 => 'Kasus Hipertensi',
            8 => 'Kasus Gangguan Jiwa',
            12 => 'Kasus Stunting/Gizi Buruk',
            13 => 'Kasus Jamban Tidak Sehat'
        ];

        $results = [];
        $params  = [];

        if($type)
        {
            $params = ['tahun' => $tahun, $type.'_id' => $id];
        }

        foreach($indikator_ids as $indikator_id => $judul_indikator)
        {
            $_params = array_merge($params, ['indikator_id'=>$indikator_id, 'status'=>'publish', 'skor'=>1]);
            $data = $db->all('iks_indikator',$_params);
            if($data)
            {
                foreach($data as $d_id => $d)
                {
                    if(!$db->exists('penduduk',['no_kk'=>$d->no_kk]))
                    {
                        unset($data[$d_id]);
                        continue;
                    }
                    $indikator = $db->single('indikator',['id'=> $indikator_id]);
                    $survey = $db->single('survey',['no_kk' => $d->no_kk,'tanggal'=>['LIKE','%'.$tahun.'%']]);
                    $nilai = json_decode($survey->nilai,1);
                    $key = array_search($indikator, array_column($nilai, 'indikator'));
                    $found = $nilai[$key]['rekap_penduduk'];

                    $key = array_search($indikator->jawaban, array_column($found, 'jawaban'));
                    $found = $found[$key];

                    $d->keluarga = $db->single('penduduk',['NIK'=>$found['penduduk']['NIK']]);
                    $d->kecamatan = $db->single('kecamatan',['id'=>$d->kecamatan_id]);
                    $d->kelurahan = $db->single('kelurahan',['id'=>$d->kelurahan_id]);
                }
            }
            $results[$indikator_id] = $data;
        }

        ob_start();
        require '../templates/rekapitulasi/tpl/kasus.php';
        $content = ob_get_clean();
        $cached = fopen($cachefile, 'w');
        fwrite($cached, $content);
        fclose($cached);
        return $content;
    }

    public function penduduk($tahun, $type = false, $id = false, $status)
    {
        $db = $this->db;

        $table   = 'penduduk';
        $auth    = auth();
        $conn    = conn();
        $db      = new Database($conn);

        $draw    = $_GET['draw'];
        $start   = $_GET['start'];
        $length  = $_GET['length'];
        $search  = $_GET['search']['value'];
        $order   = $_GET['order'];

        $fields = config('fields')['penduduk'];

        unset($fields['sebagai']);
        unset($fields['kecamatan_id']);
        unset($fields['kelurahan_id']);
        unset($fields['lingkungan_id']);

        $fields['status'] = [
            'label' => 'Status',
            'type'  => 'text'
        ];

        $columns = array_keys($fields);

        $order_by = " ORDER BY ".$columns[$order[0]['column']]." ".$order[0]['dir'];
        $status = $status != 'semua' ? "AND ".($status == 'belum survey' ? 'NOT' : '')." EXISTS (SELECT * FROM survey WHERE survey.no_kk = $table.no_kk AND survey.status='publish' AND survey.tanggal LIKE '%$tahun%')" : '';
        $where = "WHERE NIK <> '' $status";
        if($type)
        {
            $where .= " AND ".$type."_id=$id";
        }

        if(!empty($search))
        {
            $where .= " AND (NIK LIKE '%$search%' OR no_kk LIKE '%$search%' OR nama LIKE '%$search%' OR alamat LIKE '%$search%')";
        }

        $user = auth()->user;

        if(!in_array(get_role($user->id)->name,['administrator','pembina kabupaten','bupati']))
        {

            $petugas = $db->single('petugas',['user_id'=>$user->id]);
            $kecamatan_id = $petugas->kecamatan_id;
            if(!empty($kecamatan_id))
            {
                $str = "kecamatan_id=$petugas->kecamatan_id";
                $where .= $where ? " AND ".$str : "WHERE ".$str;
            }

            else
            {
                $str = "kecamatan_id=0";
                $where .= $where ? " AND ".$str : "WHERE ".$str;
            }
        }


        $db->query = "SELECT COUNT(*) as TOTAL FROM $table $where $order_by";
        $total = $db->exec('single');
        $db->query = "SELECT $table.*, (SELECT EXISTS (SELECT * FROM survey WHERE survey.no_kk = $table.no_kk AND survey.status='publish' AND survey.tanggal LIKE '%$tahun%')) as status FROM $table $where $order_by LIMIT $start,$length";
        $data  = $db->exec('all');
        $results = [];

        foreach($data as $key => $d)
        {
            $results[$key][] = $key+1;
            foreach($columns as $col)
            {
                if(in_array($col,['kecamatan_id','kelurahan_id','lingkungan_id']))
                {
                    $tbl = str_replace('_id','',$col);
                    $r   = $db->single($tbl,['id' => $d->{$col}]);
                    $results[$key][] = $r->nama;
                }
                elseif($col == 'status')
                {
                    $results[$key][] = $d->{$col} == 0 ? 'Belum Survey' : 'Sudah Survey';
                }
                else
                {
                    $results[$key][] = $d->{$col};
                }
            }
        }

        return json_encode([
            "draw" => $draw,
            "recordsTotal" => (int)$total->TOTAL,
            "recordsFiltered" => (int)$total->TOTAL,
            "data" => $results
        ]);

    }

}