<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Kecamatan</th>
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
        <?php if(isset($k->kategori) && $k->kk_nilai > 0): ?>
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