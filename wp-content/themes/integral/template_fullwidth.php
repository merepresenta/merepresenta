<?php
/**
 Template Name: Full-width Template
 * Pages for our theme
 *
 * @package WordPress
 * @subpackage Integral
 * @since Integral 1.0
 */
?>
<?php get_header(); ?>

<div class="spacer"></div>

<div class="container">

	<div class="row">

		<div class="col-md-12">

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

	</div>

</div>
<?php
	$script = get_post_meta( get_the_ID(), 'script' );

	if ($script) {
		echo "<script src='" . (get_template_directory_uri() ."/js/" . $script[0]) ."'></script>";
	}
?>

<?php
  get_footer(); 
?>