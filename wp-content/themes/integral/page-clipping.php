<?php
/* Template Name: clipping
*
* Template Post Type: page
* The template for displaying all pages.
*
* This is the template that displays all pages by default.
* Please note that this is the WordPress construct of pages
* and that other 'pages' on your WordPress site will use a
* different template.
*
*/
?>
<?php
  get_header();
?>

<div class="container">
  

  <h2 class="entry-title"><?php the_title(); ?></h2>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <p><?php the_content(); ?></p>
  <?php endwhile;?>
  <?php endif; ?>

  <div class="container carousel slide" id="page-video" data-ride="carousel"  data-interval="false">
    <!-- destacado -->
    <div class="row carousel-inner" role="listbox">
      <?php
      $cnt = 0;
      query_posts( 'cat=3' );// for videos
      while ( have_posts() ) : the_post();
        $url_video = get_post_meta( get_the_ID(), 'video' );
        $embed_video = substr($url_video[0],-11);
      ?>
      <div class="item <?=($cnt==0)?'active':''?>">
        <div class="col-md-8">
          <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" width="560" height="315" src="//www.youtube-nocookie.com/embed/<?=$embed_video?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
          </div>
        </div>
        <div class="col-md-4">
          <h4><?=the_title();?></h4>
          <h5><?php the_time('d/m/Y'); ?></h5>
          <?=the_content()?>
        </div>
      </div>
      <?php
        $cnt++;
      endwhile;
      wp_reset_query();
      ?>
    </div>
    <!-- destacado -->

    <div class="row" class="carousel-indicators">
      <?php
      $cnt = 0;
      query_posts( 'cat=3' );// for videos
      while ( have_posts() ) : the_post();
      ?>
      <div class="col-md-2" data-target="#page-video" data-slide-to="<?=$cnt?>">
        <div class="panel panel-default">
          <div class="panel-body">
            <?php
            $url_video = get_post_meta( get_the_ID(), 'video' );
            $preview_video = substr($url_video[0],-11);
            ?>
            <img src="//img.youtube.com/vi/<?=$preview_video?>/default.jpg" alt="<?=the_title();?>">
            <h4><?=the_title();?></h4>
            <h5><?php the_time('d/m/Y'); ?></h5>
          </div>
        </div>
      </div>
      <?php
        $cnt++;
      endwhile;
      wp_reset_query();
      ?>
    </div><!-- .row -->
  </div>





  <div id="carousel-clipping" class="carousel" data-ride="carousel" data-interval="false">
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
    <?php
    $cnt = 0;
    query_posts( 'cat=4' );// for videos
    while ( have_posts() ) : the_post();
      $url = get_post_meta( get_the_ID(), 'url' );
      if($url && sizeof($url) > 0) $url = $url[0];
      else $url = null;
      if (($cnt % 4) == 0) :
    ?>
      <div class="row item <?php if ($cnt == 0) { echo "active"; } ?>">
    <?php endif ?>
        <div class="col-sm-6 col-md-3">
          <span class="imagem-clipping" data-toggle="modal" data-target="#myModal-<?=$cnt?>"><?=the_post_thumbnail(array( 200, 300 ));?></span>
          <span class="titulo-materia"><?= $url == null ? the_title() : ("<a href='$url'>" . the_title() . '</a>') ?></span>
          <span class='data-materia'><?php the_time('d/m/Y'); ?></span>
        </div>  <!-- col -->
    <?php if (($cnt % 4) == 3) : ?>
    </div>  <!-- row -->
    <?php endif ?>
      
    <?php
      $cnt++;
    endwhile;
    wp_reset_query();
    if ((($cnt-1) % 4) != 3) : ?>
      </div>  <!-- row -->
    <?php endif ?>
    </div>  <!-- carousel  -->

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-clipping" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-clipping" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <?php
  $cnt = 0;
  query_posts( 'cat=4' );// for videos
  while ( have_posts() ) : the_post();
    $url = get_post_meta( get_the_ID(), 'url' );
    if($url && sizeof($url) > 0) $url = $url[0];
    else $url = null;
  ?>
  <div class="modal fade" id="myModal-<?=$cnt?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?=$cnt?>">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="model-header">
          <div class="modal-title titulo-materia" id="myModalLabel-<?=$cnt?>">
            <?=the_title()?>
          </div>
        </div>
        <div class="modal-body">
          <?=the_post_thumbnail( 'full' );?>
        </div>
        <div class="modal-footer">
          <div class="col">
            <div class="col-md-10 infos">
            <?php if ($url): ?>Matéria publicada em: <a href="<?= $url ?>" target="_blank"><?= $url ?></a>
            <?php endif ?>
            </div>
            <div class="col-md-2">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>                    
      </div> <!-- modal content  -->
    </div> <!-- modal dialog  -->
  </div>  <!-- modal fade -->
  <?php
    $cnt++;
  endwhile;
  wp_reset_query();
  ?>






  <div class="container carousel slide" id="page-fbvideo" data-ride="carousel"  data-interval="false">
    <!-- destacado -->
    <div class="row carousel-inner" role="listbox">
      <?php
      $cnt = 0;
      query_posts( 'cat=5' );// for videos
      while ( have_posts() ) : the_post();
        $url_video = get_post_meta( get_the_ID(), 'url' )[0];
      ?>
      <div class="item <?=($cnt==0)?'active':''?>">
        <div class="col-md-8">
          <div class="facebook-responsive">
            <iframe src="https://www.facebook.com/plugins/video.php?href=<?= urlencode($url_video) . '&mute=0&show_text=0&width=476'?>" width="476" height="476" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>
          </div>
        </div>
        <div class="col-md-4">
          <h4><?=the_title();?></h4>
          <h5><?php the_time('d/m/Y'); ?></h5>
          <?=the_content()?>
        </div>
      </div>
      <?php
        $cnt++;
      endwhile;
      wp_reset_query();
      ?>
    </div>
    <!-- destacado -->
    <div class="row" class="carousel-indicators">
      <?php
      $cnt = 0;
      query_posts( 'cat=5' );// for videos
      while ( have_posts() ) : the_post();
        $url_video = get_post_meta( get_the_ID(), 'url' )[0];
        preg_match('/\/(\d{10,})\//', $url_video, $matches, PREG_OFFSET_CAPTURE);
        $video_id = $matches[1][0];
      ?>
      <div class="col-md-2" data-target="#page-fbvideo" data-slide-to="<?=$cnt?>">
        <div class="panel panel-default">
          <div class="panel-body">
            <?php if (has_post_thumbnail()): the_post_thumbnail(array( 200, 300 )); else: ?>
            <img src="https://graph.facebook.com/<?=$video_id?>/picture" alt="Video image">
            <?php endif ?>
            <h4><?=the_title();?></h4>
            <h5><?php the_time('d/m/Y'); ?></h5>
          </div>
        </div>
      </div>
      <?php
        $cnt++;
      endwhile;
      wp_reset_query();
      ?> 
    </div><!-- .row -->
  </div>





</div>

<?php
  get_footer();
?>
