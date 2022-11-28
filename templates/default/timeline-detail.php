<style>
.timeline-panel{background: #FFF;border: 1px solid #eaeaea;max-width:720px;margin-left:auto;margin-right:auto}
.list-group-item-action:focus, .list-group-item-action:hover {
    background-color: transparent !important;
}
</style>
<style>
.timeline-footer {
    padding-top:10px;
    padding-bottom:10px;
    border-top:1px solid #eaeaea;
}
.timeline-footer button {
    border:0;
    background:transparent;
    cursor: pointer;
}
.timeline-footer button.like:hover, .timeline-footer button.like.active {
    color: var(--green) !important;
}
.timeline-footer button.dislike:hover, .timeline-footer button.dislike.active {
    color: var(--red) !important;
}
.timeline-footer a:hover {
    color: var(--blue) !important;
}
.timeline-footer a {
    text-decoration:none;
}
.timeline-footer a, .timeline-footer button {
    padding-right:10px;
}
.timeline>li>.timeline-panel {
    padding:0;
}
.timeline-heading {
    border-bottom:1px solid #eaeaea;
}
.timeline-heading,.timeline-footer,.timeline-body {
    padding:20px;
}
</style>
<div class="content">
    <div class="page-inner">
        <h4 class="page-title"><?=$post->title?></h4>
        <div class="row">
            <div class="col-md-12">
                <div class="timeline-panel">
                    <div class="timeline-heading">
                        <a href="<?=routeTo('timeline/detail',['id'=>$post->id])?>">
                            <h4 class="timeline-title"><b><?=$post->user->name?> - <small class="text-muted"><?=$post->date?></small></b></h4>
                        </a>
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
                        <button class="text-muted like response-btn <?=$post->post_response && $post->post_response->response_type == 'like' ? 'active' : ''?>" data-type="like" data-id="<?=$post->id?>"><i class="fas fa-fw fa-thumbs-up"></i> Suka (<?=$post->post_response_like_count?>)</button>
                        <button class="text-muted dislike response-btn <?=$post->post_response && $post->post_response->response_type == 'dislike' ? 'active' : ''?>" data-type="dislike" data-id="<?=$post->id?>"><i class="fas fa-fw fa-thumbs-down"></i> Tidak Suka (<?=$post->post_response_dislike_count?>)</button>
                        <a href="<?=routeTo('timeline/detail',['id'=>$post->id])?>" class="text-muted">
                            <i class="fas fa-fw fa-comments"></i> Komentar (<?=$post->comment_count?>)
                        </a>
                        <!-- List komentar -->
                        <div class="list-group mt-3">
                            <?php foreach($post->comments as $comment): ?>
                            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start pl-0 pr-0">
                                <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><b><?=$comment->user->name?></b></h5>
                                <small><?=$comment->date?></small>
                                </div>
                                <p class="mb-1"><?=nl2br($comment->content)?></p>
                            </a>
                            <?php endforeach ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>