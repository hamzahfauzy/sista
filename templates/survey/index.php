<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold"><?=_ucwords($table)?></h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data <?=_ucwords($table)?></h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        <?php if(is_allowed(get_route_path('survey/create',[]),auth()->user->id)): ?>
                            <a href="<?=routeTo('survey/create')?>" class="btn btn-secondary btn-round">Buat <?=_ucwords($table)?></a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if($success_msg): ?>
                            <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <div class="table-responsive table-hover table-sales">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th width="20px">#</th>
                                            <?php 
                                            foreach($fields as $field): 
                                                $label = $field;
                                                if(is_array($field))
                                                {
                                                    $label = $field['label'];
                                                }
                                                $label = _ucwords($label);
                                            ?>
                                            <th><?=$label?></th>
                                            <?php endforeach ?>
                                            <th class="text-right">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($datas as $index => $data): ?>
                                        <tr>
                                            <td>
                                                <?=$index+1?>
                                            </td>
                                            <?php 
                                            foreach($fields as $key => $field): 
                                                $label = $field;
                                                if(is_array($field))
                                                {
                                                    $label = $field['label'];
                                                    $data_value = Form::getData($field['type'],$data->{$key},true);
                                                    if($field['type'] == 'number' && floor( $data_value ) == $data_value)
                                                    {
                                                        $data_value = number_format($data_value);
                                                    }
                                                    $field = $key;
                                                }
                                                else
                                                {
                                                    $data_value = $data->{$field};
                                                }
                                                $label = _ucwords($label);
                                            ?>
                                            <td>
                                            <?=$label == 'Warna' ? "<div style='background:".$data_value.";padding:10px'></div>" : $data_value?>
                                            </td>
                                            <?php endforeach ?>
                                            <td>
                                            <?php if(is_allowed(get_route_path('survey/view',[]),auth()->user->id)): ?>
                                            <a href="<?=routeTo('survey/view',['id' => $data->id])?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Lihat</a>
                                            <?php endif ?>
                                            <?php if($data->status == 'draft'): ?>
                                            <?php if(is_allowed(get_route_path('survey/publish',[]),auth()->user->id)): ?>
                                                <a href="<?=routeTo('survey/publish',['id'=>$data->id])?>" onclick="if(confirm('apakah anda yakin akan mempublish data ini ?')){return true}else{return false}" class="btn btn-sm btn-warning"><i class="fas fa-check"></i> Publish</a>
                                            <?php endif ?>
                                            <?php if(is_allowed(get_route_path('survey/delete',[]),auth()->user->id)): ?>
                                                <a href="<?=routeTo('survey/delete',['id'=>$data->id])?>" onclick="if(confirm('apakah anda yakin akan menghapus data ini ?')){return true}else{return false}" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</a>
                                            <?php endif ?>
                                            <?php endif ?>
                                            </td>
                                        </tr>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>