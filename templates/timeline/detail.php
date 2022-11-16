<?php load_templates('layouts/top') ?>
    <style>.timeline-panel{background: #FFF;border: 1px solid #eaeaea;max-width:50%;margin-left:auto;margin-right:auto}</style>
    <div class="content">
        <div class="page-inner">
            <h4 class="page-title"><?=$title?></h4>
            <?php if($success_msg): ?>
            <p></p>
            <div class="alert alert-success"><?=$success_msg?></div>
            <?php endif ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="timeline-panel" style="max-width:50%">
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
                            <!-- List komentar -->
                            <div class="list-group">
                                <?php foreach($post->comments as $comment): ?>
                                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><b><?=$comment->user->name?></b></h5>
                                    <small><?=$comment->date?></small>
                                    </div>
                                    <p class="mb-1"><?=nl2br($comment->content)?></p>
                                </a>
                                <?php endforeach ?>
                            </div>
                            
                            <div class="form-comment">
                                <form action="<?=routeTo('timeline/post-comment',['id'=>$post->id])?>" method="post">
                                    <div class="form-group">
                                        <label for="">Komentar</label>
                                        <textarea name="content" id="" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success">Posting Komentar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php load_templates('layouts/bottom') ?>