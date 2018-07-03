<?php
/**
 * Displays header media
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>
<div class="custom-header">

		<div class="custom-header-media">
		<?php if ( twentyseventeen_is_frontpage() ): 
				the_custom_header_markup(); 
				elseif (is_page() && get_theme_mod('alternative_page_header', false) && (get_theme_mod('alternative_page_header_media', 'nothing') !== 'nothing') ) :?>
				<img src="<?php echo wp_get_attachment_image_src(get_theme_mod('alternative_page_header_media', 'nothing'), 'full')[0] ?>" alt="Testing" srcset="">
					<script type="text/javascript">
						console.log(`<?php var_dump(wp_get_attachment_image_src(get_theme_mod('alternative_page_header_media', 'nothing'), 'full'));?>`);
					</script>
				
		<?php endif;?>
		</div>

	<?php get_template_part( 'template-parts/header/site', 'branding' ); ?>

</div><!-- .custom-header -->
