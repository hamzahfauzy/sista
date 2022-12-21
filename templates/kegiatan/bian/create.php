<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Buat <?=_ucwords($table)?> Baru</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data <?=_ucwords($table)?></h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                    <a href="<?=routeTo('kegiatan/bian/index',isset($_GET['posyandu_id'])?['posyandu_id'=>$_GET['posyandu_id']]:[])?>" class="btn btn-warning btn-round">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if($error_msg): ?>
                            <div class="alert alert-danger"><?=$error_msg?></div>
                            <?php endif ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <?php 
                                foreach($fields as $key => $field): 
                                    $label = $field;
                                    $type  = "text";
                                    if(is_array($field))
                                    {
                                        $field_data = $field;
                                        $field = $key;
                                        $label = $field_data['label'];
                                        if(isset($field_data['type']))
                                        $type  = $field_data['type'];
                                    }
                                    $label = _ucwords($label);
                                    $fieldname = $table."[".$field."]";
                                    if($type == 'file')
                                    {
                                        $fieldname = $field;
                                    }
                                    $attr = [
                                        'class'=>($type == 'color' ? 'd-block' :'form-control'),
                                        "placeholder"=>$label,
                                        "value"=>isset($old[$field])?$old[$field]:($penduduk->{$field}??''),
                                        'required' => 'required'
                                    ];
                                    if(isset($penduduk->{$field}) && $field != 'usia')
                                    {
                                        $attr['readonly'] = 'readonly';
                                    }

                                    if($field == 'bulan')
                                    {
                                        $attr['class'] .= ' select2-bulan';
                                    }
                                ?>
                                <div class="form-group">
                                    <label for=""><?=$label?></label>
                                    <?= Form::input($type, $fieldname, $attr) ?>
                                </div>
                                <?php endforeach ?>
                                <div class="form-group">
                                    <button class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>