<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Dusun / Lingkungan</th>
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

<div class="card-title">Rekapitulasi Data Profil Kesehatan Keluarga Kecamatan <?=$detail_kelurahan->kecamatan->nama?>, <?=$detail_kelurahan->nama?>
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
                <th>% Cakupan Desa / Kelurahan</th>
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
            $presentase = $presentase*100 . '%';
            ?>
            <td style="color:#FFF;background:<?=$warna?>"><?=$presentase?></td>
        </tr>
        <?php endforeach ?>
        <tr>
            <td colspan="2">Indeks Keluarga Sehat IKS</td>
            <?php foreach($iks as $k): ?>
            <td style="color:#FFF;background:<?=isset($k->kategori) ? $k->kategori->warna : '#FFF'?>"><?=number_format($k->total_skor*100,2)?>%</td>
            <?php endforeach ?>
            <td style="color:#FFF;background:<?=$iks_kelurahan->warna?>"><?=number_format($skor_iks_kelurahan*100,2)?>%</td>
        </tr>
    </table>
</div>