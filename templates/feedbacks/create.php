<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Buat Tindak Lanjut Permasalahan</h2>
                        <h5 class="text-white op-7 mb-2">Memanajemen data Tindak Lanjut Permasalahan</h5>
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
                            <?php if($error_msg): ?>
                            <div class="alert alert-danger"><?=$error_msg?></div>
                            <?php endif ?>
                            <form action="" method="post">
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
                                    $attribute = [
                                        'class'=>($type == 'color' ? 'd-block' :'form-control'),"placeholder"=>$label,"value"=>$old[$field]??$data->{$field}
                                    ];
                                    
                                    $fieldname = $table."[".$field."]";
                                    
                                    if($field == 'clause_dest_item')
                                    {
                                        $attribute['multiple'] = 'multiple';
                                        $attribute['class'] .= ' select2';
                                        $fieldname .= "[]";
                                    }
                                ?>
                                <div class="form-group">
                                    <label for=""><?=$label?></label>
                                    <?= Form::input($type, $fieldname, $attribute) ?>
                                </div>
                                <?php endforeach ?>
                                <div class="form-group">
                                    <button class="btn btn-success">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php load_templates('layouts/bottom') ?>