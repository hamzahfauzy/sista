<?php load_templates('layouts/top') ?>
<style>
.tableFixHead          { overflow: auto; height: 100px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1; }

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
                        <h2 class="text-white pb-2 fw-bold">Detail Surve</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Survey</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>NO</th>
                                        <th>NIK</th>
                                        <th>NAMA</th>
                                        <th>STATUS</th>
                                    </tr>
                                    <?php foreach($data->nilai[0]->rekap_penduduk as $index => $k): ?>
                                    <tr>
                                        <td><?=$index+1?></td>
                                        <td><?=$k->penduduk->NIK?></td>
                                        <td><?=$k->penduduk->nama?></td>
                                        <td><?=$k->penduduk->sebagai?></td>
                                    </tr>
                                    <?php endforeach ?>
                                </table>
                            </div>
                            <div class="form-group">
                                <label for="">Berkas Survey</label>
                                <br>
                                <a href="<?=asset($data->berkas)?>">Download</a>
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <br>
                                <?php if($data->status == 'draft'): ?>
                                <i>Draft</i>
                                <?php else: ?>
                                <span style="background:<?=$data->kategori->warna?>;padding:10px;color:#FFF;"><?=$data->kategori->nama?></span>
                                <?php endif ?>
                            </div>
                            <div class="form-group">
                                <div class="table-responsive">

                                    <table class="table table-bordered tableFixHead">
                                        <thead>
                                            <tr>
                                                <td style="text-align:center;width:30%" rowspan="3">INDIKATOR</td>
                                                <td style="text-align:center" colspan="<?=count($data->nilai[0]->rekap_penduduk)*3?>">VARIABEL PENILAIAN</td>
                                                <td rowspan="3">Skor</td>
                                            </tr>
                                            <tr>
                                                <?php foreach($data->nilai[0]->rekap_penduduk as $k): ?>
                                                <td style="text-align:center" colspan="3"><?=$k->penduduk->nama?></td>
                                                <?php endforeach ?>
                                            </tr>
                                            <tr>
                                                <?php foreach($data->nilai[0]->rekap_penduduk as $k): ?>
                                                <td style="text-align:center;background:blue;">N</td>
                                                <td style="text-align:center;background:green;">Y</td>
                                                <td style="text-align:center;background:red;">T</td>
                                                <?php endforeach ?>
                                            </tr>
                                        </thead>
                                        <?php 
                                        $all_skor = [];
                                        foreach($data->nilai as $nilai): 
                                            $all_skor[] = $nilai->skor;
                                        ?>
                                        <tr>
                                            <td><?=$nilai->indikator->nama?></td>
                                            <?php 
                                            foreach($nilai->rekap_penduduk as $penduduk): 
                                                if($penduduk->jawaban != 'disable'): 
                                            ?>
                                            <td style="text-align:center;">
                                                <input type="radio" <?=$penduduk->jawaban == 'N' ? 'checked' : 'disabled' ?> value="N" style="transform:scale(1.5)">
                                            </td>
                                            <td style="text-align:center;">
                                                <input type="radio" <?=$penduduk->jawaban == 'Y' ? 'checked' : 'disabled' ?> value="Y" style="transform:scale(1.5)">
                                            </td>
                                            <td style="text-align:center;">
                                                <input type="radio" <?=$penduduk->jawaban == 'T' ? 'checked' : 'disabled' ?> value="T" style="transform:scale(1.5)">
                                            </td>
                                            <?php else: ?>
                                            <td style="background:silver;"></td>
                                            <td style="background:silver;"></td>
                                            <td style="background:silver;"></td>
                                            <?php endif; ?>
                                            <?php endforeach ?>
                                            <td><?=$nilai->skor?></td>
                                        </tr>
                                        <?php 
                                        endforeach;
                                        $nilai = array_count_values($all_skor);
                                        $question = array_sum($nilai) - ($nilai['N']??0);
                                        if(isset($nilai['N'])) unset($nilai['N']);
                                        $nilai = ($nilai[1] - $nilai[0]) / $question;
                                        ?>
                                        <tr>
                                            <td>Total <?php ?></td>
                                            <td style="text-align:center" colspan="<?=count($data->nilai[0]->rekap_penduduk)*3?>"></td>
                                            <td ><?=number_format($nilai,2)?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>