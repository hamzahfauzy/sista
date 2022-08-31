<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">PAK SURYA TAUFIK ASAHAN (PASTA)</h2>
                        <h5 class="text-white op-7 mb-2">Program Aplikasi Survey Pelayanan dan Pemantauan Fase Indeks Kesehatan di Kabupaten Asahan</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row mt--2">
                <div class="col-12">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="card-title">Statistik Daerah</div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                                        <div class="card-header">Kecamatan</div>
                                        <div class="card-body">
                                            <h1><?=$kecamatan?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                                        <div class="card-header">Desa/Kelurahan</div>
                                        <div class="card-body">
                                            <h1><?=$kelurahan?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                                        <div class="card-header">Dusun/Lingkungan</div>
                                        <div class="card-body">
                                            <h1><?=$lingkungan?></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                                        <div class="card-header">Penduduk</div>
                                        <div class="card-body">
                                            <h1><?=$penduduk?></h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt--2">
                <div class="col-12">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="card-title">
                                Statistik Indeks Keluarga Sehat (IKS) 
                                Kecamatan <a href="<?=routeTo('default/kecamatan',['bulan' => (int) $iks[0]->periode[1],'tahun' => (int) $iks[0]->periode[0],'kecamatan_id'=>$detail_lingkungan->kecamatan->id])?>" class="text-primary"><?=$detail_lingkungan->kecamatan->nama?></a>, 
                                <a href="<?=routeTo('default/kelurahan',['bulan' => (int) $iks[0]->periode[1],'tahun' => (int) $iks[0]->periode[0],'kelurahan_id'=>$detail_lingkungan->kelurahan->id])?>" class="text-primary"><?=$detail_lingkungan->kelurahan->nama?></a>,
                                <?=$detail_lingkungan->nama?>
                            </div>
                            <br>
                            <div class="filter">
                                <form action="">
                                    <input type="hidden" name="lingkungan_id" value="<?=$detail_lingkungan->id?>">
                                    <div class="d-flex">
                                        <select name="bulan" id="" class="form-control" required>
                                            <?php 
                                            $b = isset($_GET['bulan']) ? $_GET['bulan'] : date('m'); 
                                            $t = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'); 
                                            foreach(bulanIndo() as $index => $bulan): ?>
                                            <option value="<?=$index?>" <?=$b==$index ? 'selected=""' : '' ?>><?=$bulan?></option>
                                            <?php endforeach ?>
                                        </select>
                                        &nbsp;
                                        <select name="tahun" id="" class="form-control">
                                            <?php for($i=date('Y');$i>=1990;$i--): ?>
                                            <option <?=$t==$i ? 'selected=""' : '' ?>><?=$i?></option>
                                            <?php endfor ?>
                                        </select>
                                        &nbsp;
                                        <button class="btn btn-success">Tampilkan</button>
                                    </div>
                                </form>
                                <p></p>
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <th>#</th>
                                    <th>No KK</th>
                                    <th>Status</th>
                                </tr>
                                <?php foreach($iks as $index => $k): ?>
                                <tr>
                                    <td><?=$index+1?></td>
                                    <td>
                                        <a href="<?=routeTo('survey/view',['id'=>$k->survey->id])?>"><?=$k->no_kk?></a>
                                    </td>
                                    <td>
                                        <?php if(isset($k->kategori)): ?>
                                        <span style="background:<?=$k->kategori->warna?>;padding:10px;color:#FFF;"><?=$k->kategori->nama?></span>
                                        <?php else: ?>
                                        <i>Tidak ada survey</i>
                                        <?php endif ?>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>