<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Posyandu : <?=$data->nama?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Posyandu</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="index.php?r=roles/index" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <center>
                                <a href="<?=routeTo('kegiatan/imunisasi/index',['posyandu_id'=>$data->id])?>" class="btn btn-primary">Imunisasi Balita</a>
                                <a href="<?=routeTo('kegiatan/ibu-hamil/index',['posyandu_id'=>$data->id])?>" class="btn btn-primary">Pemeriksaan Ibu Hamil</a>
                                <a href="<?=routeTo('kegiatan/pemantauan-gizi/index',['posyandu_id'=>$data->id])?>" class="btn btn-primary">Pemantauan Gizi Balita</a>
                                <a href="<?=routeTo('kegiatan/kb/index',['posyandu_id'=>$data->id])?>" class="btn btn-primary">Keluarga Berencana</a>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>