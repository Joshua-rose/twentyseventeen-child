<?php
/**
 * Template part for displaying pages on front page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

global $twentyseventeencounter;

?>

<article id="panel<?php echo $twentyseventeencounter; ?>" <?php post_class( 'twentyseventeen-panel ' ); ?> >

	<?php if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'twentyseventeen-featured-image' );

		// Calculate aspect ratio: h / w * 100%.
		if (get_theme_mod('panel_'.$twentyseventeencounter.'_Show_Title', true)):
			$ratio = $thumbnail[2] / $thumbnail[1] * 100;
			$useTitle = get_theme_mod('panel_'.$twentyseventeencounter.'_Headline_Use_Title', true);
			$headline = get_theme_mod('panel_'.$twentyseventeencounter.'_Headline');
			if ($useTitle){
				$headline = get_the_title();
			}
			$h_style = get_theme_mod('panel_'.$twentyseventeencounter.'_Headline_style');
			$subheadline = get_theme_mod('panel_'.$twentyseventeencounter.'_SubHeadline');
			$s_style = get_theme_mod('panel_'.$twentyseventeencounter.'_SubHeadline_style');
		endif;//end Show Title
	?>

		<div class="panel-image" style="background-image: url(<?php echo esc_url( $thumbnail[0] ); ?>);">
			
			<?php
			if (!empty($headline) || !empty($subheadline)):
				?>
				<div class="panel-image-prop" style="padding-top: <?php echo esc_attr( $ratio ); ?>%; position:relative;">
				<div class="panel-image-text" ><?php echo (empty($headline)? '' : '<h2>'.$headline.'</h2>'); echo (empty($subheadline)? '' : '<h3>'.$subheadline.'</h3>'); ?></div>
			<?php
			else:
				?>
				<div class="panel-image-prop" style="padding-top: <?php echo esc_attr( $ratio ); ?>%;">
				<?php
			
			endif;
			?>
			
			</div>
		</div><!-- .panel-image -->

	<?php endif; ?>

	<div class="panel-content">
		<div class="wrap">
			<?php if (get_theme_mod('panel_'.$twentyseventeencounter.'_Show_Title')): ?>
			<header class="entry-header">
				<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>

				<?php twentyseventeen_edit_link( get_the_ID() ); ?>

			</header><!-- .entry-header -->
		<?php endif; ?>
			<div class="entry-content">
				<?php
					/* translators: %s: Name of current post */
					the_content( sprintf(
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
						get_the_title()
					) );
				?>
			</div><!-- .entry-content -->

			<?php
			// Show recent blog posts if is blog posts page (Note that get_option returns a string, so we're casting the result as an int).
			if ( get_the_ID() === (int) get_option( 'page_for_posts' )  ) : ?>

				<?php // Show four most recent posts.
				$recent_posts = new WP_Query( array(
					'posts_per_page'      => 3,
					'post_status'         => 'publish',
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				) );
				?>

		 		<?php if ( $recent_posts->have_posts() ) : ?>

					<div class="recent-posts">

						<?php
						while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
							get_template_part( 'template-parts/post/content', 'excerpt' );
						endwhile;
						wp_reset_postdata();
						?>
					</div><!-- .recent-posts -->
				<?php endif; ?>
			<?php endif; ?>

		</div><!-- .wrap -->
	</div><!-- .panel-content -->

</article><!-- #post-## -->
