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
                <div class="col-3 mb-2">
                    <div class="card-body shadow-lg bg-white rounded">
                        <div class="row">
                            <div class="col">
                                <h5 class="text-uppercase text-muted mb-0">Dusun / Lingkungan</h5>
                                <span class="h2 font-weight-bold mb-0"><?=number_format($lingkungan)?></span>
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
                <div class="col-3 mb-2">
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
                <div class="col-3">
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
                <div class="col-3">
                    <div class="card-body shadow-lg bg-white rounded">
                        <div class="row">
                            <div class="col">
                                <h5 class="text-uppercase text-muted mb-0">IKS Kelurahan</h5>
                                <span class="h2 font-weight-bold mb-0"><?=$iks_kelurahan->nama?></span>
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
                            <div class="card-title">Statistik Indeks Keluarga Sehat (IKS) Kecamatan <a href="<?=routeTo('default/kecamatan',['bulan' => (int) $iks[0]->periode[1],'tahun' => (int) $iks[0]->periode[0],'kecamatan_id'=>$detail_kelurahan->kecamatan_id])?>" class="text-primary"><?=$detail_kelurahan->kecamatan->nama?></a>, <?=$detail_kelurahan->nama?></div>
                            <br>
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
                                        <button class="btn btn-success">Tampilkan</button>
                                    </div>
                                </form>
                                <p></p>
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <th>#</th>
                                    <th>Dusun / Lingkungan</th>
                                    <th>Status</th>
                                </tr>
                                <?php foreach($iks as $index => $k): ?>
                                <tr>
                                    <td><?=$index+1?></td>
                                    <td>
                                        <a href="<?=routeTo('default/lingkungan',['tahun'=>$k->periode,'lingkungan_id'=>$k->id])?>"><?=$k->nama?></a>
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