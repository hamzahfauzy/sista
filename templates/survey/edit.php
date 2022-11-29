<?php load_templates('layouts/top') ?>
<style>
.tableFixHead          { overflow: auto; height: 800px; }
.tableFixHead thead { position: sticky; top: 0; z-index: 1; }

/* Just common table stuff. Really. */
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
</style>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Edit Survey</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Survey</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                    <a href="<?=routeTo('survey/index')?>" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>NO</th>
                                        <th>NIK</th>
                                        <th>NAMA</th>
                                        <th>STATUS</th>
                                    </tr>
                                    <?php foreach($data->nilai[0]->rekap_penduduk as $index => $k): ?>
                                    <tr>
                                        <td><?=$index+1?></td>
                                        <td><?=$k->penduduk->NIK?></td>
                                        <td><?=$k->penduduk->nama?></td>
                                        <td><?=$k->penduduk->sebagai?></td>
                                    </tr>
                                    <?php endforeach ?>
                                </table>
                            </div>
                            <div class="form-group">
                                <div class="table-responsive">
                                    <div class="tableFixHead">
                                        <table class="table table-bordered tableFixHead">
                                            <thead>
                                                <tr>
                                                    <th style="text-align:center;width:30%" rowspan="3">INDIKATOR</th>
                                                    <th style="text-align:center" colspan="<?=count($data->nilai[0]->rekap_penduduk)*3?>">VARIABEL PENILAIAN</th>
                                                </tr>
                                                <tr>
                                                    <?php foreach($data->nilai[0]->rekap_penduduk as $k): ?>
                                                    <th style="text-align:center" colspan="3"><?=$k->penduduk->nama?></th>
                                                    <?php endforeach ?>
                                                </tr>
                                                <tr>
                                                    <?php foreach($data->nilai[0]->rekap_penduduk as $k): ?>
                                                    <th style="text-align:center;background:blue;">N</th>
                                                    <th style="text-align:center;background:green;">Y</th>
                                                    <th style="text-align:center;background:red;">T</th>
                                                    <?php endforeach ?>
                                                </tr>
                                            </thead>
                                            <?php 
                                            foreach($indikator as $i): 
                                                $peng = explode(',',$i->pengaturan); 
                                            ?>
                                            <tr>
                                                <td><?=$i->nama?></td>
                                                <?php 
                                                foreach($data->nilai[0]->rekap_penduduk as $rk):
                                                    $k = $rk->penduduk;
                                                    $p = $k->sebagai == 'Ayah' ? 'ayah' : ($k->sebagai == 'Ibu' ? 'ibu' : pengaturan($k->tanggal_lahir));
                                                    if(in_array($p,$peng)): 
                                                        $statusJawaban = getStatusJawaban($data->nilai,$i,$k);
                                                ?>
                                                <td style="text-align:center;">
                                                    <?php if($i->id <= 14): ?>
                                                    <input type="radio" name="pengaturan[<?=$i->id?>][<?=$k->id?>]" <?=$statusJawaban == 'N' ? 'checked' : '' ?> value="N" style="transform:scale(1.5)">
                                                    <?php endif ?>
                                                </td>
                                                <td style="text-align:center;">
                                                    <input type="radio" name="pengaturan[<?=$i->id?>][<?=$k->id?>]" <?=$statusJawaban == 'Y' ? 'checked' : '' ?> value="Y" style="transform:scale(1.5)">
                                                </td>
                                                <td style="text-align:center;">
                                                    <input type="radio" name="pengaturan[<?=$i->id?>][<?=$k->id?>]" <?=$statusJawaban == 'T' ? 'checked' : '' ?> value="T" style="transform:scale(1.5)">
                                                </td>
                                                <?php else: ?>
                                                <td style="background:silver;"><input type="hidden" name="pengaturan[<?=$i->id?>][<?=$k->id?>]" value="disable"></td>
                                                <td style="background:silver;"></td>
                                                <td style="background:silver;"></td>
                                                <?php endif; ?>
                                                <?php endforeach ?>
                                            </tr>
                                            <?php endforeach ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success">Submit</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
setTimeout(() => {
    document.querySelector('.wrapper').classList.add('sidebar_minimize')
}, 1000);
</script>
<?php load_templates('layouts/bottom') ?>