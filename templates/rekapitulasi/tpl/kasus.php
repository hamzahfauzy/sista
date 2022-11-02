<?php $nomor = 1; foreach($results as $indikator_id => $r): ?>
<h2><?=$nomor++.'. '.$indikator_ids[$indikator_id]?></h2>
<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>ID Keluarga</th>
        <th>Anggota Keluarga</th>
        <th>Kecamatan</th>
        <th>Desa / Kelurahan</th>
    </tr>
    <?php if(empty($r)): ?>
    <tr>
        <td colspan="5"><i>Tidak ada data</i></td>
    </tr>
    <?php endif ?>
    <?php 
    $no = 1;
    if(!empty($r))
    foreach($r as $index => $k): 
        if(isset($k->keluarga))
        foreach($k->keluarga as $kel):
    ?>
    <tr>
        <td><?=$no++?></td>
        <td><?=$k->no_kk?></td>
        <td><?=$kel->nama?></td>
        <td><?=$k->kecamatan->nama?></td>
        <td><?=$k->kelurahan->nama?></td>
    </tr>
    <?php endforeach ?>
    <?php endforeach ?>
</table>
<?php endforeach ?>