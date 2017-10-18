<?php get_header(); ?>

<?php 
  $file_dir = get_template_directory();
  if(is_page('show-politician')) {
    require_once($file_dir . "/mostra_politico.php");
  }
  elseif (is_page('query-politician')) {
    require_once($file_dir . "/query.php");
  }
  else {
?>


<h1><?php the_title(); ?></h1>
  
<?php
  if ( have_posts() ) {
    while ( have_posts() ) {
      the_post();
      ?>

      <div><?php the_content(); ?></div>
      <?php 
    }
  }
}
get_footer();
