<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">SI SURYA TAUFIK ASAHAN (SISTA)</h2>
                        <h5 class="text-white op-7 mb-2">Sistem Informasi Survey Pelayanan dan Pemantauan Fase Indeks Kesehatan di Kabupaten Asahan</h5>
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
                            <div class="card-body">
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
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>