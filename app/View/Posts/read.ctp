<?php $this->assign('title', $post['Post']['title']); ?>
<script>
$(document).ready(function(){
    $(window).load(function(){
        $('#twitter_share').fadeIn('slow');
    });

    $(".confirm").confirm({
        text: "Voulez vous vraiment supprimer cet article ?",
        title: "Confirmation",
        confirmButton: "Oui",
        cancelButton: "Non"
    });

    var nbLikes = <?php echo $nbLikes; ?>;

    $('#like').on('click', function(){
        $('#chargement').show();
        $('#like').hide();
        var id = '<?php echo $this->params['pass'][1]; ?>';
        $.post('<?php echo $this->webroot.'posts/like'; ?>', {id: id}, function(){
            $('#chargement').hide();
            $('#dislike').fadeIn();
            nbLikes++;
            $('#dislike').html('<font color="red"><i class="fa fa-heart"></i></font> J\'aime (' + nbLikes + ')');
        });
    });

    $('#dislike').on('click', function(){
        $('#chargement').show();
        $('#dislike').hide();
        var id = '<?php echo $this->params['pass'][1]; ?>';
        $.post('<?php echo $this->webroot.'posts/like'; ?>', {id: id}, function(){
            $('#chargement').hide();
            $('#like').fadeIn();
            nbLikes--;
            $('#like').html('<i class="fa fa-heart"></i> J\'aime (' + nbLikes + ')');
        });
    });

    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');
});
</script>
<!--=== Content Part ===-->
<div class="container content blog-page blog-item">		
    <!--Post-->
    <div class="row magazine-page">
        <div class="col-md-9">
        	<div class="blog margin-bottom-40">
                <div class="blog-img">
                    <?php
                    if(filter_var($post['Post']['img'], FILTER_VALIDATE_URL)){
                        echo $this->Html->image($post['Post']['img'], array('class' => 'img-responsive', 'style' => 'width:100%;'));
                    }
                    else{
                        echo $this->Html->image('/img/posts/'.$post['Post']['img'], array('class' => 'img-responsive', 'style' => 'width:100%;'));
                    }
                    ?>
                </div>
                <br>
            	<h3><font color="#7AC02C"><?php echo $post['Post']['title']; ?></font></h3>
                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <div class="blog-post-tags">
                            <ul class="list-unstyled list-inline blog-info">
                                <li><i class="fa fa-user"></i> Posté par <?php echo $post['Post']['author']; ?></li>
                                <li>
                                    <?php
                                    if($post['Post']['draft'] == 1):
                                    ?>
                                    <i class="fa fa-calendar"></i> <?php echo $this->Time->timeAgoInWords($post['Post']['created']); ?>
                                    <?php else: ?>
                                    <i class="fa fa-calendar"></i> <?php echo $this->Time->timeAgoInWords($post['Post']['posted']); ?>
                                    <?php endif; ?>
                                </li>
                                <li><i class="fa fa-tags"></i> <?php echo $post['Post']['cat']; ?></li>
                            </ul>                    
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group pull-right">
                            <?php if($liked): ?>
                                <button class="btn btn-default btn-xs rounded-3x" id="dislike">
                                    <font color="red"><i class="fa fa-heart"></i></font> J'aime (<?php echo $nbLikes; ?>)
                                </button>
                                <button class="btn btn-default btn-xs rounded-3x" id="like" style="display:none;">
                                    <i class="fa fa-heart"></i> J'aime (<?php echo $nbLikes; ?>)
                                </button>
                            <?php else: ?>
                                <button class="btn btn-default btn-xs rounded-3x" id="dislike" style="display:none;">
                                    <font color="red"><i class="fa fa-heart"></i></font> J'aime (<?php echo $nbLikes; ?>)
                                </button>
                                <button class="btn btn-default btn-xs rounded-3x" id="like">
                                    <i class="fa fa-heart"></i> J'aime (<?php echo $nbLikes; ?>)
                                </button>
                            <?php endif; ?>        
                            <button class="btn btn-default btn-xs rounded-3x" id="chargement" style="display:none;">
                                <?php echo $this->Html->image('like_loader.gif', array('alt' => 'chargement')); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php if($this->Session->read('Auth.User.role') > 0): ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="btn-group">
                                <a class="btn btn-default btn-xs" href="<?php echo $this->Html->webroot.'posts/edit/'.$post['Post']['id']; ?>">
                                    <font color="#777777"><i class="fa fa-pencil"></i> Editer</font>
                                </a>
                                <?php
                                if($this->Session->read('Auth.User.username') == 'Bestt_Alex' OR $this->Session->read('Auth.User.username') == 'Mansot'):
                                    if($post['Post']['draft'] == 1):
                                        if($post['Post']['corrected'] == 0):
                                        ?>
                                        <a class="btn btn-default btn-xs" href="<?php echo $this->Html->webroot.'posts/corrected/'.$post['Post']['id']; ?>">
                                            <font color="green"><i class="fa fa-check"></i> Corrigé</font>
                                        </a>
                                        <?php
                                        endif;
                                    endif;
                                endif;
                                ?>
                                <a class="confirm btn btn-default btn-xs" href="<?php echo $this->Html->webroot.'posts/delete/'.$post['Post']['id']; ?>">
                                    <font color="red"><i class="fa fa-times"></i> Supprimer</font>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="btn-group pull-right">
                                <?php
                                if($post['Post']['draft'] == 1):
                                    echo '<a class="btn btn-danger btn-xs" href=""><i class="fa fa-lock"></i> Cet article est enregistré en tant que brouillon</a>';
                                    if($post['Post']['corrected'] == 0):
                                        echo '<a class="btn btn-warning btn-xs" href=""><i class="fa fa-exclamation-triangle"></i> Cet article n\'a pas été corrigé</a>';
                                    else:
                                        echo '<a class="btn btn-success btn-xs" href=""><i class="fa fa-check"></i> Cet article a été corrigé</a>';
                                    endif;
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <hr>
                <?php echo $post['Post']['content']; ?>
            </div>
            <div class="hidden-xs hidden-sm">
                <hr>
                <h2><a href="">Derniers articles</a></h2>
                <div class="row">
                    <?php $nb = -1; ?>
                    <?php while($nb < 2): ?>
                        <div class="col-md-4">
                            <div class="magazine-news-img">
                                <?php
                                $nb++;
                                echo '<a href="'.$this->Html->url(array('controller' => 'posts', 'action' => 'read', 'slug' => $lastPosts[$nb]['Post']['slug'], 'id' => $lastPosts[$nb]['Post']['id'])).'">';
                                if(filter_var($lastPosts[$nb]['Post']['img'], FILTER_VALIDATE_URL)):
                                    echo $this->Html->image($lastPosts[$nb]['Post']['img'], array('alt' => '', 'height' => '130', 'width' => '260'));
                                else:
                                    echo $this->Html->image('posts/'.$lastPosts[$nb]['Post']['img'], array('alt' => '', 'height' => '130', 'width' => '260'));
                                endif;
                                echo '</a><br>';
                                echo '<a href="'.$this->Html->url(array('controller' => 'posts', 'action' => 'read', 'slug' => $lastPosts[$nb]['Post']['slug'], 'id' => $lastPosts[$nb]['Post']['id'])).'">'.$lastPosts[$nb]['Post']['title'].'</a>';
                                ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <hr>
            <div id="disqus_thread"></div>
            <noscript>Please enable JavaScript to view the comments</noscript>
        </div>
        <!--End Post-->
        <?php echo $this->element('sidebar'); ?>
    </div>
</div><!--/container-->		
<!--=== End Content Part ===-->