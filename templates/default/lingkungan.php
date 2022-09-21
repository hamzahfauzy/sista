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
            <div class="row mt--2">
                <div class="col-12 col-md-4 mb-2">
                    <div class="card-body shadow-lg bg-white rounded">
                        <div class="row">
                            <div class="col">
                                <h5 class="text-uppercase text-muted mb-0">Penduduk</h5>
                                <span class="h2 font-weight-bold mb-0"><?=number_format($penduduk)?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-danger text-white rounded-circle shadow p-1 px-2">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-nowrap">Jumlah Total</span>
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card-body shadow-lg bg-white rounded">
                        <div class="row">
                            <div class="col">
                                <h5 class="text-uppercase text-muted mb-0">Jumlah KK</h5>
                                <span class="h2 font-weight-bold mb-0"><?=number_format($jumlah_kk)?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-danger text-white rounded-circle shadow p-1 px-2">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-nowrap">Jumlah Total</span>
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card-body shadow-lg bg-white rounded">
                        <div class="row">
                            <div class="col">
                                <h5 class="text-uppercase text-muted mb-0">IKS Lingkungan</h5>
                                <span class="h2 font-weight-bold mb-0"><?=$iks_lingkungan->nama?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-danger text-white rounded-circle shadow p-1 px-2">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-nowrap">Status</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner">
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
                            <div class="card-title">
                            Rekapitulasi Data Profil Kesehatan Keluarga 
                            Kecamatan <a href="<?=routeTo('default/kecamatan',['tahun' => $_GET['tahun'],'kecamatan_id'=>$detail_lingkungan->kecamatan->id])?>" class="text-primary"><?=$detail_lingkungan->kecamatan->nama?></a>, 
                            <a href="<?=routeTo('default/kelurahan',['tahun' => $_GET['tahun'],'kelurahan_id'=>$detail_lingkungan->kelurahan->id])?>" class="text-primary"><?=$detail_lingkungan->kelurahan->nama?></a>,
                            <?=$detail_lingkungan->nama?>    
                            </div>
                            <br>

                            <div class="table-responsive">
                                <table class="table table-bordered tableFixHead">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>INDIKATOR</th>
                                            <?php foreach($all_survey as $survey): ?>
                                            <th>ID : <a href="<?=routeTo('survey/view',['id'=>$survey->id])?>"><?=$survey->no_kk?></a></th>
                                            <?php endforeach ?>
                                            <th>Keluarga Bernilai 1</th>
                                            <th>Keluarga Bernilai N</th>
                                            <th>Keluarga Belum Di Survey</th>
                                            <th>% Cakupan Lingkungan</th>
                                        </tr>
                                    </thead>
                                    <?php foreach($indikator as $index => $i): ?>
                                    <tr>
                                        <td><?=$index+1?></td>
                                        <td><?=$i->nama?></td>
                                        <?php 
                                        $n = ['1'=>0,'N'=>0,'0'=>0];
                                        foreach($all_survey as $survey): 
                                            $nilai = $survey->nilai[$index];
                                            $n[$nilai->skor]++;
                                        ?>
                                        <td><?=$nilai->skor?></td>
                                        <?php 
                                        endforeach;
                                        $unsurvey = $jumlah_kk - count($iks);
                                        $total_1 = $n['1'];
                                        $pembagi = $n['1']+$n['0']+$unsurvey;
                                        if($total_1 == 0 && $pembagi == 0 && $n['N'])
                                        {
                                            $warna = 'blue';
                                            $presentase = 'N';
                                        }
                                        else if(in_array(0,[$total_1, $pembagi]))
                                        {
                                            $warna = 'red';
                                            $presentase = '0%';
                                        }
                                        else
                                        {
                                            $presentase = number_format( $total_1 /  $pembagi, 3 );
                                            $db->query = "SELECT * FROM kategori WHERE nilai_awal <= $presentase AND nilai_akhir >= $presentase";
                                            $warna = $db->exec('single')->warna;
                                            $presentase = $presentase*100 . '%';
                                        }
                                        ?>
                                        <td><?= $n['1'] ?></td>
                                        <td><?= $n['N'] ?></td>
                                        <td><?= $unsurvey ?></td>
                                        <td style="color:#FFF;background:<?=$warna?>"><?= $presentase ?></td>
                                    </tr>
                                    <?php endforeach ?>
                                    <tr>
                                        <td colspan="2">Indeks Keluarga Sehat IKS</td>
                                        <?php foreach($all_survey as $survey): ?>
                                        <td style="color:#FFF;background:<?=$survey->kategori->warna?>"><?=number_format($survey->total_skor,3)?></td>
                                        <?php endforeach ?>
                                        <td colspan="3"></td>
                                        <td style="color:#FFF;background:<?=$iks_lingkungan->warna?>"><?=$skor_iks_lingkungan?></td>
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