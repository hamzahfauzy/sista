<?php if(isset($_GET['print'])): ?>
<style>
table {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

table td, table th {
  border: 1px solid #ddd;
  padding: 8px;
}

table tr:nth-child(even){background-color: #f2f2f2;}

table tr:hover {background-color: #ddd;}

table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
.card-title {
    text-align:center;
    font-weight:bold;
    font-size:20px;
    margin-top:10px;
    margin-bottom:10px;
    display:block;
}
.card-title a {
    text-decoration:none;
    color:#000;
}
</style>
<script>window.print()</script>
<?php endif ?>
<?php if(!isset($_GET['print'])): ?>
<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">PAK SURYA-TAUFIK ASAHAN (PASTA)</h2>
                        <h5 class="text-white op-7 mb-2">Program Aplikasi Survey Pelayanan dan Pemantauan Fase Indeks Kesehatan di Kabupaten Asahan</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row">
                <div class="col-12">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="filter">
                                <form action="">
                                    <input type="hidden" name="kelurahan_id" value="<?=$detail_kelurahan->id?>">
                                    <div class="d-flex">
                                        <?php $t = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');  ?>
                                        <select name="tahun" id="" class="form-control">
                                            <?php for($i=date('Y');$i>=1990;$i--): ?>
                                            <option <?=$t==$i ? 'selected=""' : '' ?>><?=$i?></option>
                                            <?php endfor ?>
                                        </select>
                                        &nbsp;
                                        <button class="btn btn-success" name="view">Tampilkan</button>
                                        &nbsp;
                                        <button class="btn btn-success" name="print">Cetak</button>
                                    </div>
                                </form>
                                <p></p>
                            </div>
                            <?php endif ?>
                            <div class="card-title">Statistik Indeks Kesehatan Keluarga (IKS) Kecamatan <a href="<?=routeTo('rekapitulasi/kecamatan',['tahun' => $_GET['tahun'],'kecamatan_id'=>$detail_kelurahan->kecamatan_id])?>" class="text-primary"><?=$detail_kelurahan->kecamatan->nama?></a>, <?=$detail_kelurahan->nama?></div>
                            <br>
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
                                    <td>
                                        <a href="<?=routeTo('rekapitulasi/lingkungan',['tahun'=>$k->periode,'lingkungan_id'=>$k->id])?>"><?=$k->nama?></a>
                                    </td>
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

                            <div class="card-title">Rekapitulasi Data Profil Kesehatan Keluarga 
                            Kecamatan <a href="<?=routeTo('rekapitulasi/kecamatan',['tahun' => $_GET['tahun'],'kecamatan_id'=>$detail_kelurahan->kecamatan_id])?>" class="text-primary"><?=$detail_kelurahan->kecamatan->nama?></a>, <?=$detail_kelurahan->nama?>
                            </div>
                            <br>

                            <div class="table-responsive">
                                <table class="table table-bordered tableFixHead">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>INDIKATOR</th>
                                            <?php foreach($iks as $k): ?>
                                            <th>
                                                <a href="<?=routeTo('rekapitulasi/lingkungan',['tahun'=>$k->periode,'lingkungan_id'=>$k->id])?>"><?=$k->nama?></a>
                                            </th>
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
<?php if(!isset($_GET['print'])): ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>
<?php endif ?>