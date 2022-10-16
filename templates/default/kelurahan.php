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
                                <h5 class="text-uppercase text-muted mb-0">Dusun / Lingkungan</h5>
                                <span class="h2 font-weight-bold mb-0"><?=number_format($lingkungan)?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-danger text-white rounded-circle shadow p-1 px-2">
                                    <?php if(is_allowed(get_route_path('crud/index',['table'=>'lingkungan']),auth()->user->id)): ?>
                                    <a href="<?=routeTo('crud/index',['table'=>'lingkungan'])?>"><i class="fas fa-chart-bar"></i></a>
                                    <?php else: ?>
                                    <i class="fas fa-chart-bar"></i>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-nowrap">Jumlah Total</span>
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <div class="card-body shadow-lg bg-white rounded">
                        <div class="row">
                            <div class="col">
                                <h5 class="text-uppercase text-muted mb-0">Penduduk</h5>
                                <span class="h2 font-weight-bold mb-0"><?=number_format($penduduk)?></span>
                            </div>
                            <div class="col-auto">
                                <div class="icon icon-shape bg-danger text-white rounded-circle shadow p-1 px-2">
                                    <?php if(is_allowed(get_route_path('crud/index',['table'=>'penduduk']),auth()->user->id)): ?>
                                    <a href="<?=routeTo('crud/index',['table'=>'penduduk'])?>"><i class="fas fa-chart-bar"></i></a>
                                    <?php else: ?>
                                    <i class="fas fa-chart-bar"></i>
                                    <?php endif ?>
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
                                    <?php if(is_allowed(get_route_path('survey/index',[]),auth()->user->id)): ?>
                                    <a href="<?=routeTo('survey/index',[])?>"><i class="fas fa-chart-bar"></i></a>
                                    <?php else: ?>
                                    <i class="fas fa-chart-bar"></i>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-muted text-sm">
                            <span class="text-nowrap">Jumlah Total</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>