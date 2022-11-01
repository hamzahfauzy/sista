<div class="card-title">Rekapitulasi dan Prioritas Masalah Indeks Keluarga Sehat Kecamatan <?=$detail_kelurahan->kecamatan->nama?>, <?=$detail_kelurahan->nama?>
</div>
<br>

<div class="table-responsive">
    <table class="table table-bordered tableFixHead">
        <thead>
            <tr>
                <th>No</th>
                <th>INDIKATOR</th>
                <?php foreach($iks as $k): ?>
                <th><?=$k->nama?></th>
                <?php endforeach ?>
                <th style="text-transform: uppercase;">Target</th>
                <th style="text-transform: uppercase;">Realisasi Cakupan Desa / Kelurahan</th>
                <th style="text-transform: uppercase;">Permasalahan</th>
            </tr>
        </thead>
        <?php foreach($indikator as $index => $i): ?>
        <tr>
            <td><?=$index+1?></td>
            <td><?=$i->nama?></td>
            <?php 
            $total = 0;
            foreach($iks as $k): 
                $total += $k->iks_per_indikator?$k->iks_per_indikator[$index]['presentase']:0;
            ?>
            <td><?=$k->iks_per_indikator?number_format($k->iks_per_indikator[$index]['presentase']*100,2).'%':'-'?></td>
            <?php 
            endforeach;
            $presentase = number_format( $total/count($iks), 3 );
            $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $presentase AND nilai_akhir >= $presentase";
            $warna = $db->exec('single')->warna;
            $kekurangan = 100-($presentase*100) . '%';
            $presentase = $presentase*100 . '%';
            ?>
            <td>100%</td>
            <td style="color:#FFF;background:<?=$warna?>"><?=$presentase?></td>
            <td><?=$kekurangan?></td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="2"></td>
            <?php foreach($iks as $k): ?>
            <td style="color:#FFF;background:<?=isset($k->kategori) ? $k->kategori->warna : '#FFF'?>"><?=number_format($k->total_skor*100,2)?>%</td>
            <?php endforeach ?>
            <td></td>
            <td style="color:#FFF;background:<?=$iks_kelurahan->warna?>"><?=number_format($skor_iks_kelurahan*100,2)?>%</td>
            <td><?=number_format(100-($skor_iks_kelurahan*100),2)?>%</td>
        </tr>
    </table>
</div>