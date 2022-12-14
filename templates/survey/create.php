<?php load_templates('layouts/top') ?>
    <?php require '_modal-penduduk.php' ?>
    <?php require '_modal-anak.php' ?>
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
                        <h2 class="text-white pb-2 fw-bold">Buat <?=_ucwords($table)?> Baru</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data <?=_ucwords($table)?></h5>
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
                            <?php if($error_msg): ?>
                            <div class="alert alert-danger"><?=$error_msg?></div>
                            <?php endif ?>
                            <form action="">
                                <div class="form-group">
                                    <label for="">Tanggal Pelaksanaan Survey</label>
                                    <input type="date" id="tanggal_pelaksanaan_survey" name="tanggal" class="form-control" placeholder="Input Tanggal" value="<?=isset($_GET['tanggal'])?$_GET['tanggal']:''?>" required>
                                </div>
                                <?php if(isset($_GET['no_kk'])): ?>
                                <div class="form-group">
                                    <label for="">ID Keluarga</label>
                                    <input type="text" name="no_kk" class="form-control" value="<?=isset($_GET['no_kk'])?$_GET['no_kk']:''?>" required>
                                </div>
                                <?php else: ?>
                                <div class="form-group">
                                    <label for="">NIK Ayah</label>
                                    <div class="d-flex">
                                        <input type="text" name="nik_ayah" class="form-control" placeholder="Input NIK Ayah" value="<?=isset($_GET['nik_ayah'])?$_GET['nik_ayah']:''?>">
                                        &nbsp;
                                        <button type="button" class="btn btn-success" onclick="targetPenduduk('ayah')">Cari</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">NIK Ibu</label>
                                    <div class="d-flex">
                                        <input type="text" name="nik_ibu" class="form-control" placeholder="Input NIK Ibu" value="<?=isset($_GET['nik_ibu'])?$_GET['nik_ibu']:''?>">
                                        &nbsp;
                                        <button type="button" class="btn btn-success" onclick="targetPenduduk('ibu')">Cari</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">NIK Anak (Jika anak lebih dari 1, maka pisahkan dengan koma tanpa spasi)</label>
                                    <div class="d-flex">
                                        <input type="text" name="nik_anak" class="form-control" placeholder="Input NIK Anak" value="<?=isset($_GET['nik_anak'])?$_GET['nik_anak']:''?>">
                                        &nbsp;
                                        <button type="button" class="btn btn-success" onclick="targetAnak()">Cari</button>
                                    </div>
                                </div>
                                <?php endif ?>
                                <div class="form-group">
                                    <button class="btn btn-success" name="filter">Submit</button>
                                </div>
                            </form>

                            <?php if(isset($_GET['no_kk'])): ?>
                                <?php if(!$keluarga): ?>
                                    <span class="badge badge-danger">Maaf! Data tidak ditemukan</span>
                                <?php else: ?>
                                    <?php if($data): ?>
                                        <span class="badge badge-warning">Maaf! No KK ditemukan tetapi sudah melakukan survey. Harap gunakan NIK yang lain</span>
                                    <?php else: ?>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="no_kk" value="<?=$_GET['no_kk']?>">
                                            <div class="form-group">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th>NO</th>
                                                        <th>NIK</th>
                                                        <th>NAMA</th>
                                                        <th>STATUS</th>
                                                    </tr>
                                                    <?php foreach($keluarga as $index => $k): ?>
                                                    <tr>
                                                        <td><?=$index+1?></td>
                                                        <td><?=$k->NIK?></td>
                                                        <td><?=$k->nama?></td>
                                                        <td><?=$k->sebagai?></td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                </table>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Berkas Survey</label>
                                                <input type="file" name="berkas" id="" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <div class="table-responsive">
                                                    <div class="tableFixHead">
                                                        <table class="table table-bordered tableFixHead">
                                                            <thead>
                                                                <tr>
                                                                    <th style="text-align:center;width:30%" rowspan="3">INDIKATOR</th>
                                                                    <th style="text-align:center" colspan="<?=count($keluarga)*3?>">VARIABEL PENILAIAN</th>
                                                                </tr>
                                                                <tr>
                                                                    <?php foreach($keluarga as $k): ?>
                                                                    <th style="text-align:center" colspan="3"><?=$k->nama?></th>
                                                                    <?php endforeach ?>
                                                                </tr>
                                                                <tr>
                                                                    <?php foreach($keluarga as $k): ?>
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
                                                                foreach($keluarga as $k): 
                                                                    $p = $k->sebagai == 'Ayah' ? 'ayah' : ($k->sebagai == 'Ibu' ? 'ibu' : pengaturan($k->tanggal_lahir));
                                                                    if(in_array($p,$peng)): 
                                                                ?>
                                                                <td style="text-align:center;">
                                                                    <?php if($i->id <= 14): ?>
                                                                    <input type="radio" name="pengaturan[<?=$i->id?>][<?=$k->id?>]" value="N" style="transform:scale(1.5)" required>
                                                                    <?php endif ?>
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    <input type="radio" name="pengaturan[<?=$i->id?>][<?=$k->id?>]" value="Y" style="transform:scale(1.5)" required>
                                                                </td>
                                                                <td style="text-align:center;">
                                                                    <input type="radio" name="pengaturan[<?=$i->id?>][<?=$k->id?>]" value="T" style="transform:scale(1.5)" required>
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
                                                <button class="btn btn-success">Simpan Survey</button>
                                            </div>
                                        </form>
                                    <?php endif ?>
                                <?php endif ?>
                            <?php endif ?>
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