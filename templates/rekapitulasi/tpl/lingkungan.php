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
                <?php if($all_survey): ?>
                <?php $survey = $all_survey[0]; ?>
                <th>ID : <?=$survey->no_kk?></th>
                <?php if(isset($all_survey[1])): ?>
                <th>ID : <?=$all_survey[1]->no_kk?> - <?= end($all_survey)->no_kk?></th>
                <?php endif ?>
                <?php endif ?>
                <th>Keluarga Bernilai 1</th>
                <th>Keluarga Bernilai N</th>
                <th>Keluarga Belum Di Survey</th>
                <th>% Cakupan Lingkungan</th>
                <th>% Permasalahan</th>
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
            ?>
            <td><?=$nilai->skor?></td>
            <?php 
            if(isset($all_survey[1])): 
                $skor_n = ['1'=>0,'N'=>0,'0'=>0];
                foreach($all_survey as $key => $survey)
                {
                    if($key == 0) continue;
                    $nilai = $survey->nilai[$index];
                    $n[$nilai->skor]++;
                    $skor_n[$nilai->skor]++;
                }
            ?>
            <td><?php foreach($skor_n as $l => $v){ echo $l.'='.$v.'<br>'; }?></td>
            <?php 
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
            <td><?= $n['1'] ?></td>
            <td><?= $n['N'] ?></td>
            <td><?= $unsurvey ?></td>
            <td style="color:#FFF;background:<?=$warna?>"><?= $presentase ?></td>
            <td><?= $kurangan ?></td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="2">Indeks Keluarga Sehat IKS</td>
            <?php if($all_survey): ?>
            <?php $survey = $all_survey[0]; ?>
            <td style="color:#FFF;<?=isset($survey->kategori)?'background:'.$survey->kategori->warna:''?>"><?=number_format($survey->total_skor*100,2)?>%</td>
            <?php 
            if(isset($all_survey[1])): 
            $skor = 0; 
            foreach($all_survey as $key => $survey){ 
                if($key == 0) continue;
                $skor += $survey->total_skor; 
            }
            ?>
            <td><?=number_format(($skor/(count($all_survey)-1))*100,2)?>%</td>
            <?php endif ?>
            <?php endif ?>
            <td colspan="3"></td>
            <td style="color:#FFF;background:<?=$iks_lingkungan->warna?>"><?=number_format($skor_iks_lingkungan*100,2)?></td>
            <td></td>
        </tr>
    </table>
</div>