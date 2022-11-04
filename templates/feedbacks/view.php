<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Umpan Balik</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Umpang Balik</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <a href="<?=routeTo('feedbacks/index')?>" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped">
                                <tr>
                                    <td style="vertical-align:top !important;padding-top:10px !important;"><b>Kepada</b></td>
                                    <td style="vertical-align:top !important;padding-top:10px !important;"><?=ucwords($data->clause_dest)?></td>
                                </tr>
                                <?php if($data->clause_dest_item): ?>
                                <tr>
                                    <td style="vertical-align:top !important;padding-top:10px !important;"><b>Tujuan</b></td>
                                    <td style="vertical-align:top !important;padding-top:10px !important;"><?=ucwords($data->clause_dest_item)?></td>
                                </tr>
                                <?php endif ?>
                                <tr>
                                    <td style="vertical-align:top !important;padding-top:10px !important;"><b>Instruksi</b></td>
                                    <td style="vertical-align:top !important;padding-top:10px !important;">
                                        <b><?=$data->topik?></b>
                                        <p><?=$data->content?></p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>