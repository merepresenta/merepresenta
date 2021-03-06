<?php 
	get_header(); 

  $file_dir = get_template_directory();
  if(is_page('politicos')) {
    require_once($file_dir . "/mostra_politico.php");
  }
  elseif (is_page('representou')||is_page('quem-te-representa')) {
    require_once($file_dir . "/query.php");
  }
  else {
?>

<div class="spacer"></div>

<div class="container">

	<div class="row">

		<div class="<?php if ( is_active_sidebar( 'rightbar' ) ) : ?>col-md-8<?php else : ?>col-md-12<?php endif; ?>">

			<div class="content">
			
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			     <h2 class="entry-title"><?php the_title(); ?></h2>
			    
			     <div class="entry">

			       <?php the_content(); ?>

			     </div>

			     
			 </div>
			
			 <?php endwhile;?>

			 <?php endif; ?>
			

			</div>

		</div>

		<?php get_sidebar(); ?>

	</div>

</div>

<?php 
	}
	get_footer(); 
?>