<?php foreach($results as $indikator_id => $r): ?>
<h2><?=$indikator_ids[$indikator_id]?></h2>
<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>ID Keluarga</th>
        <th>Anggota Keluarga</th>
        <th>Kecamatan</th>
        <th>Desa / Kelurahan</th>
    </tr>
    <?php foreach($r as $index => $k): ?>
    <tr>
        <td><?=$index+1?></td>
        <td><?=$k->no_kk?></td>
        <td><?=$k->keluarga->nama?></td>
        <td><?=$k->kecamatan->nama?></td>
        <td><?=$k->kelurahan->nama?></td>
    </tr>
    <?php endforeach ?>
</table>
<?php endforeach ?>