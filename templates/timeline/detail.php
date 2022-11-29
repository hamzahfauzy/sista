<?php load_templates('layouts/top') ?>
    <style>
    .timeline-panel{background: #FFF;border: 1px solid #eaeaea;max-width:720px;margin-left:auto;margin-right:auto}
    .list-group-item-action:focus, .list-group-item-action:hover {
        background-color: transparent !important;
    }
    </style>
    <div class="content">
        <div class="page-inner">
            <h4 class="page-title"><?=$title?></h4>
            <?php if($success_msg): ?>
            <p></p>
            <div class="alert alert-success"><?=$success_msg?></div>
            <?php endif ?>
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
                            <div class="list-group mt-3 comment-lists">
                            </div>
                            
                            <div class="form-comment">
                                <form action="<?=routeTo('timeline/post-comment',['id'=>$post->id])?>" method="post" name="comment_form" onsubmit="submitComment(); return false;">
                                    <div class="form-group mb-2 mt-3" style="padding:0">
                                        <textarea name="content" id="" cols="30" class="form-control" style="resize:none;" placeholder="Komentar anda disini..."></textarea>
                                    </div>
                                    <div class="form-group" style="padding:0">
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
    <script>
    var post_id = <?=$post->id?>;
    function submitComment()
    {
        var formData = new FormData(comment_form)
        formData.append('post_id',post_id)
        $('.btn-post-submit').html('Memproses...')
        fetch('/api/timeline/submit-comment',{
            method:'POST',
            body:formData
        }).then(res => {
            if(res.ok)
            {
                comment_form.reset()
                return res.json()
            }
        })
        return false

        // posting_timeline.submit();
    }
    // check new comment trigger
    var lastCommentId       = 0
    var allCommentId        = []
    var new_comment_trigger = null
    async function newCommentTrigger()
    {
        var request = await fetch('/api/timeline/get-all-comments?post_id='+post_id)
        if(request.ok)
        {
            var response = await request.json()
            if(response.data && response.data[response.data.length-1].id != lastCommentId)
            {
                var allCommentContents = document.querySelector('.comment-lists')
                var oldContent = allCommentContents.innerHTML
                var newContent = ""
                for(var i = 0; i < response.data.length; i++)
                {
                    var post = response.data[i]
                    if(allCommentId.includes(post.id)) continue
                    allCommentId.push(post.id)
                    newContent += `
                                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start pl-0 pr-0">
                                    <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><b>${post.user.name}</b></h5>
                                    <small>${post.date}</small>
                                    </div>
                                    <p class="mb-1">${nl2br(post.content)}</p>
                                </a>
`
                    lastCommentId = post.id
                }
                allCommentContents.innerHTML = oldContent + newContent
            }

        }
    }
    function nl2br (str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

    new_comment_trigger = setInterval(newCommentTrigger, 2000)
    </script>
<?php load_templates('layouts/bottom') ?>