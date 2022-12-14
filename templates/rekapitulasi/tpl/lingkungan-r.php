<div class="card-title">
    Rekapitulasi dan Prioritas Masalah Indeks Keluarga Sehat 
    Kecamatan <?=$detail_lingkungan->kecamatan->nama?>, 
    <?=$detail_lingkungan->kelurahan->nama?>,
    <?=$detail_lingkungan->nama?>    
    </div>
<br>

<div class="table-responsive">
    <table class="table table-bordered tableFixHead">
        <thead>
            <tr>
                <th>No</th>
                <th>INDIKATOR</th>
                <th style="text-transform: uppercase;">Target</th>
                <th style="text-transform: uppercase;">Realisasi Cakupan Lingkungan</th>
                <th style="text-transform: uppercase;">Permasalahan</th>
            </tr>
        </thead>
        <?php foreach($indikator as $index => $i): ?>
        <tr>
            <td><?=$index+1?></td>
            <td><?=$i->nama?></td>
            <?php 
            $n = ['1'=>0,'N'=>0,'0'=>0];
            if($all_survey): 
                $survey = $all_survey[0]; 
                $nilai = $survey->nilai[$index];
                $n[$nilai->skor]++;
            if(isset($all_survey[1])): 
                $skor_n = ['1'=>0,'N'=>0,'0'=>0];
                foreach($all_survey as $key => $survey)
                {
                    if($key == 0) continue;
                    $nilai = $survey->nilai[$index];
                    $n[$nilai->skor]++;
                    $skor_n[$nilai->skor]++;
                }
                endif;
            endif;
            $unsurvey = $jumlah_kk - count($iks);
            $total_1 = $n['1'];
            $pembagi = $n['1']+$n['0']+$unsurvey;
            if(($total_1 == 0 && $pembagi == 0 && $n['N']))
            {
                $warna = 'blue';
                $presentase = 'N';
            }
            else if($n['1'] == 0 && $n['0'] == 0)
            {
                $kurangan = '0%';
                $warna = 'green';
                $presentase = '100%';
            }
            else if(in_array(0,[$total_1, $pembagi]))
            {
                $warna = 'red';
                $presentase = '0%';
                $kurangan = '100%';
            }
            else
            {
                $presentase = number_format( $total_1 /  $pembagi, 3 );
                $_presentase = ceil( $total_1 /  $pembagi);
                $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $_presentase AND nilai_akhir >= $_presentase";
                $warna = $db->exec('single')->warna;
                $kurangan = 100-($presentase*100) . '%';
                $presentase = $presentase*100 . '%';
            }
            ?>
            <td>100%</td>
            <td style="color:#FFF;background:<?=$warna?>"><?= $presentase ?></td>
            <td><?= $kurangan ?></td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="2"></td>
            <td></td>
            <td style="color:#FFF;background:<?=$iks_lingkungan->warna?>"><?=number_format($skor_iks_lingkungan*100,2)?></td>
            <td></td>
        </tr>
    </table>
</div>