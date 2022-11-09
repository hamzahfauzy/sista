<style>
.tableFixHead          { overflow: auto; height: 800px; }
.tableFixHead thead { position: sticky; top: 0; z-index: 1; }

/* Just common table stuff. Really. */
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
</style>
<h2>Form Survey Mandiri</h2>
<table class="table table-bordered table-striped">
    <tr>
        <td><b>Kecamatan</b></td>
        <td><?=$penduduk->kecamatan->nama?></td>
    </tr>
    <tr>
        <td><b>Desa / Kelurahan</b></td>
        <td><?=$penduduk->kelurahan->nama?></td>
    </tr>
    <tr>
        <td><b>Dusun / Lingkungan</b></td>
        <td><?=$penduduk->lingkungan->nama?></td>
    </tr>
</table>

<h3>Data Keluarga</h3>
<form action="">
<input type="hidden" name="nik" value="<?=$_GET['nik']?>">
<input type="hidden" name="page" value="form-survey">
<table class="table table-bordered table-striped">
    <tr>
        <th>NO</th>
        <th>NIK</th>
        <th>NAMA</th>
        <th>STATUS</th>
        <th>AKTIF</th>
    </tr>
    <?php foreach($penduduk->keluarga as $index => $k): ?>
    <tr>
        <td><?=$index+1?></td>
        <td><?=$k->NIK?></td>
        <td><?=$k->nama?></td>
        <td><?=$k->sebagai?></td>
        <td><input type="checkbox" value="<?=$k->id?>" name="aktif[]" <?=!isset($_GET['aktif']) || (isset($_GET['aktif']) && in_array($k->id, $_GET['aktif'])) ? 'checked' : ''?> <?=isset($_GET['aktif']) ? 'disabled' : ''?>></td>
    </tr>
    <?php endforeach ?>
</table>
<?php if(!isset($_GET['isi-survey'])): ?>
<button class="btn btn-success" name="isi-survey">Isi Survey</button>
<?php endif ?>
</form>

<?php if(isset($_GET['isi-survey'])): ?>

<form action="" method="post" style="padding-bottom:200px">
    <h3>Indikator</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tr>
                <td>No</td>
                <td>Deskripsi</td>
                <td>Pilihan</td>
            </tr>
            <?php foreach($indikator_tambahan as $idx => $indi): ?>
            <tr>
                <td><?=$idx+1?></td>
                <td><?=$indi->deskripsi?></td>
                <td>
                    <select name="indikator_tambahan[<?=$indi->id?>]" id="" required class="form-control">
                    <option value="">- Pilih -</option>
                    <?php foreach(explode("\n",$indi->pilihan) as $pilihan): ?>
                    <option><?=$pilihan?></option>
                    <?php endforeach ?>
                    </select>
                </td>
            </tr>
            <?php endforeach ?>
        </table>
    </div>

    <h3>Formulir</h3>
    <div class="table-responsive">
        <div class="tableFixHead">
            <table class="table table-bordered tableFixHead">
                <thead>
                    <tr>
                        <th style="text-align:center;width:30%" rowspan="3">INDIKATOR</th>
                        <th style="text-align:center" colspan="<?=count($penduduk->keluarga)*3?>">VARIABEL PENILAIAN</th>
                    </tr>
                    <tr>
                        <?php foreach($penduduk->keluarga as $k): if(!in_array($k->id,$_GET['aktif'])) continue; ?>
                        <th style="text-align:center" colspan="3"><?=$k->nama?></th>
                        <?php endforeach ?>
                    </tr>
                    <tr>
                        <?php foreach($penduduk->keluarga as $k): if(!in_array($k->id,$_GET['aktif'])) continue; ?>
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
                    foreach($penduduk->keluarga as $k): 
                        if(!in_array($k->id,$_GET['aktif'])) continue;
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

    <p></p><p></p>
    <button class="btn btn-success">Simpan Survey</button>
</form>
<?php endif ?>