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
            <span data-toggle="modal" data-target="#myModal-<?=$cnt?>"><?=the_post_thumbnail(array( 200, 300 ));?></span>
            <h4><?= $url == null ? the_title() : ("<a href='$url'>" . the_title() . '</a>') ?></h4>
            <h5><?php the_time('d/m/Y'); ?></h5>
            <div class="modal fade" id="myModal-<?=$cnt?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <?=the_post_thumbnail( 'full' );?>
              </div>
            </div>
        </div>
    <?php if (($cnt % 4) == 3) : ?>
    </div>
    <?php endif ?>
      
    <?php
      $cnt++;
    endwhile;
    wp_reset_query();
    if ((($cnt-1) % 4) != 3) : ?>
      </div>
    <?php endif ?>
    </div>

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




  <div class="container carousel slide" id="page-fbvideo" data-ride="carousel"  data-interval="false">
    <!-- destacado -->
    <div class="row carousel-inner" role="listbox">
      <?php
      $cnt = 0;
      query_posts( 'cat=5' );// for videos
      while ( have_posts() ) : the_post();
        $url_video = get_post_meta( get_the_ID(), 'url' );
        $embed_video = $url_video[0];
      ?>
      <div class="item <?=($cnt==0)?'active':''?>">
        <div class="col-md-8">
          <div class="facebook-responsive">
            <iframe src="https://www.facebook.com/plugins/video.php?href=<?= urlencode($embed_video) . '&mute=0&show_text=0&width=476'?>" width="476" height="476" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>
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
        $url_video = get_post_meta( get_the_ID(), 'url' );
        $embed_video = $url_video[0];
      ?>
      <div class="col-md-2" data-target="#page-fbvideo" data-slide-to="<?=$cnt?>">
        <div class="panel panel-default">
          <div class="panel-body">
            <?=the_post_thumbnail(array( 150, 300 ));?>
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
