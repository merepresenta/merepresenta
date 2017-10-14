<?php get_header(); ?>

<?php 
  if(is_page('show-politician')) {
    require_once(get_template_directory() . "/mostra_politico.php");
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
