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
    <?php 
    $no = 1;
    if(!empty($r))
    foreach($r as $index => $k): 
        if(isset($k->keluarga)):
        foreach($k->keluarga as $kel):
    ?>
    <tr>
        <td><?=$no++?></td>
        <td><a href="<?=routeTo('survey/view',['id'=>$k->survey_id])?>"><?=$k->no_kk?></a></td>
        <td><?=$kel->nama?></td>
        <td><?=$k->kecamatan->nama?></td>
        <td><?=$k->kelurahan->nama?></td>
    </tr>
    <?php endforeach ?>
    <?php endif ?>
    <?php endforeach ?>

    <?php if($no == 1): ?>
    <tr>
        <td colspan="5"><i>Tidak ada data</i></td>
    </tr>
    <?php endif ?>
</table>
<?php endforeach ?>