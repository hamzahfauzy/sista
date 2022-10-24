<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Desa / Kelurahan</th>
        <th>Total KK</th>
        <th>KK Sudah Dinilai</th>
        <th>KK Belum Dinilai</th>
        <th>Status</th>
    </tr>
    <?php foreach($iks as $index => $k): ?>
    <tr>
        <td><?=$index+1?></td>
        <td><?=$k->nama?></td>
        <td><?=$k->jumlah_kk?></td>
        <td><?=$k->kk_nilai?></td>
        <td><?=$k->kk_belum_nilai?></td>
        <?php if(isset($k->kategori)): ?>
        <td style="background:<?=$k->kategori->warna?>;color:#FFF;">
            <?=$k->kategori->nama?>
        <?php else: ?>
        <td>
            <i>Tidak ada survey</i>
        <?php endif ?>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<div class="card-title">
Rekapitulasi dan Prioritas Masalah Indeks Keluarga Sehat
Kecamatan <?=$detail_kecamatan->nama?></div>
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
                <th>Target</th>
                <th>Realisasi Cakupan Kecamatan</th>
                <th>Permasalahan</th>
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
            <td colspan="2">Indeks Keluarga Sehat IKS</td>
            <?php foreach($iks as $k): ?>
            <td style="color:#FFF;<?=isset($k->kategori) ? 'background:'.$k->kategori->warna : ''?>"><?=number_format($k->total_skor*100,2)?></td>
            <?php endforeach ?>
            <td></td>
            <td style="color:#FFF;background:<?=$iks_kecamatan->warna?>"><?=number_format($skor_iks_kecamatan*100,2)?></td>
            <td><?=number_format(100-($skor_iks_kecamatan*100),2)?>%</td>
        </tr>
    </table>
</div>