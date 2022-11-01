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
    <?php 
    $no = 1;
    foreach($r as $index => $k): 
        foreach($k->keluarga as $kel):
    ?>
    <tr>
        <td><?=$no+1?></td>
        <td><?=$k->no_kk?></td>
        <td><?=$kel->nama?></td>
        <td><?=$k->kecamatan->nama?></td>
        <td><?=$k->kelurahan->nama?></td>
    </tr>
    <?php endforeach ?>
    <?php endforeach ?>
</table>
<?php endforeach ?>