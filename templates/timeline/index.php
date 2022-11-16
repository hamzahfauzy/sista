<?php load_templates('layouts/top') ?>
    <!-- Modal -->
    <style>.timeline-panel{background: #FFF;border: 1px solid #eaeaea;max-width:720px;margin-left:auto;margin-right:auto;margin-bottom:15px;}</style>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Posting Timeline</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="posting-timeline" method="post" action="<?=routeTo('timeline/post')?>" id="posting_timeline" name="posting_timeline" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="">Konten</label>
                        <textarea name="content" id="content" cols="30" rows="10" class="form-control" style="resize:none;" placeholder="Konten timeline anda disini..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Berkas</label>
                        <input type="file"  id="fileinput" class="form-control" name="files[]" multiple="multiple" accept="image/jpeg,image/gif,image/png">
                    </div>
                    <?php /*
                    <div class="form-group">
                        <label for="">Status</label>
                        <?= Form::input('options:Publish|Draft', 'status', ['class'=>'form-control']) ?>
                    </div> */ ?>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitPost()">Simpan</button>
            </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="page-inner">
            <h4 class="page-title">Time Line</h4>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">
            Posting Timeline
            </button>
            <?php if($success_msg): ?>
            <p></p>
            <div class="alert alert-success"><?=$success_msg?></div>
            <?php endif ?>
            <div class="row">
                <div class="col-md-12">
                    <?php foreach($posts as $index => $post): ?>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title"><b><?=$post->user->name?> - <small class="text-muted"><?=$post->date?></small></b></h4>
                            </div>
                            <div class="timeline-body">
                                <p><?=nl2br($post->content)?></p>
                                <?php if($post->files): ?>
                                <p>
                                    <br>
                                </p>
                                <div class="grid-container">
                                    <?php
                                    $files_counter = count($post->files);
                                    $class = $files_counter == 1 ? 'w-6 h-3' : '';
                                    $class = $files_counter == 2 ? 'w-3 h-2' : $class;
                                    foreach($post->files as $file_index => $file):
                                        $c = $class; 
                                        if($c == '')
                                        {
                                            if(($file_index+1) % 5 == 1)
                                            $c = 'w-3 h-3';
                                            else if(($file_index+1) % 5 == 2)
                                            $c = 'w-3 h-2';  
                                        }
                                    ?>
                                    <div class="gallery-container <?=$c?>">
                                        <div class="gallery-item">
                                            <div class="images"><img src="<?=asset($file->file_url)?>" alt="<?=$post->user->name.' - '.($file_index+1)?>">
                                            </div>
                                            <div class="title"><?=$post->user->name.' - '.($file_index+1)?></div>
                                        </div>
                                    </div>
                                    <?php endforeach ?>
                                </div>
                                <?php endif ?>
                            </div>
                            <div class="timeline-footer">
                                <a href="<?=routeTo('timeline/detail',['id'=>$post->id])?>" class="text-muted">
                                    <i class="fas fa-fw fa-comments"></i> Komentar
                                </a>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
    <script>
    function submitPost()
    {
        var input = document.getElementById('fileinput');
        if (input.files.length) {
            // var file = input.files[0];
            // addPara("File " + file.name + " is " +  + " bytes in size");
            // console.log(input.files)
            var isValidated = true
            for(var i=0;i<input.files.length;i++)
            {
                var file = input.files[i];
                var fileSize = file.size/1024/1024
                if(fileSize > 0.6)
                {
                    isValidated = false
                    break
                }
            }

            if(!isValidated)
            {
                alert("File tidak boleh lebih dari 500kb")
                return
            }
        }
        posting_timeline.submit();
    }
    </script>

<?php load_templates('layouts/bottom') ?>