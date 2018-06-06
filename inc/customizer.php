<?php
/**
 * Twenty Seventeen: Customizer
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function twentyseventeen_child_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport          = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport   = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport  = 'postMessage';

	$wp_customize->selective_refresh->add_partial( 'blogname', array(
		'selector' => '.site-title a',
		'render_callback' => 'twentyseventeen_customize_partial_blogname',
	) );
	$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
		'selector' => '.site-description',
		'render_callback' => 'twentyseventeen_customize_partial_blogdescription',
	) );

	/**
	 * Custom colors.
	 */
	$wp_customize->add_setting( 'colorscheme', array(
		'default'           => 'light',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'twentyseventeen_sanitize_colorscheme',
	) );

	$wp_customize->add_setting( 'colorscheme_hue', array(
		'default'           => 250,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint', // The hue is stored as a positive integer.
	) );

	$wp_customize->add_control( 'colorscheme', array(
		'type'    => 'radio',
		'label'    => __( 'Color Scheme', 'twentyseventeen' ),
		'choices'  => array(
			'light'  => __( 'Light', 'twentyseventeen' ),
			'dark'   => __( 'Dark', 'twentyseventeen' ),
			'custom' => __( 'Custom', 'twentyseventeen' ),
		),
		'section'  => 'colors',
		'priority' => 5,
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'colorscheme_hue', array(
		'mode' => 'hue',
		'section'  => 'colors',
		'priority' => 6,
	) ) );

	/**
	 * Logo Placement options
	 */
	$wp_customize->add_setting('logo_placement', array(
		'default' => 'navigation'
	));
	$wp_customize->add_control('logo_placement', array(
		'lable' => 'Select the location of the of the custom logo',
		'section' => 'title_tagline',
		'type' => 'radio',
		'description' => 'WHen the navigation location is selected the custom logo will only appear in the navigation area.',
		'choices' => array(
			'navigation' => 'Navigation',
			'header' => 'Over Header Image'
		),
		'active_callback' => 'twentyseventeen_is_static_front_page',
	));
	$wp_customize->selective_refresh->add_partial( 'logo_placement', array(
			'selector'            => '#logo_placement',
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );
	/**
	 * Theme options.
	 */
	$wp_customize->add_section( 'theme_options', array(
		'title'    => __( 'Theme Options', 'twentyseventeen' ),
		'priority' => 130, // Before Additional CSS.
	) );

	$wp_customize->add_setting( 'page_layout', array(
		'default'           => 'two-column',
		'sanitize_callback' => 'twentyseventeen_sanitize_page_layout',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( 'page_layout', array(
		'label'       => __( 'Page Layout', 'twentyseventeen' ),
		'section'     => 'theme_options',
		'type'        => 'radio',
		'description' => __( 'When the two-column layout is assigned, the page title is in one column and content is in the other.', 'twentyseventeen' ),
		'choices'     => array(
			'one-column' => __( 'One Column', 'twentyseventeen' ),
			'two-column' => __( 'Two Column', 'twentyseventeen' ),
		),
		'active_callback' => 'twentyseventeen_is_view_with_layout_option',
	) );
	
	/**
	 * Filter number of front page sections in Twenty Seventeen.
	 *
	 * @since Twenty Seventeen 1.0
	 *
	 * @param int $num_sections Number of front page sections.
	 */
	$num_sections = apply_filters( 'twentyseventeen_front_page_sections', 4 );

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
		$wp_customize->add_setting( 'panel_' . $i, array(
			'default'           => false,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		) );

		$wp_customize->add_control( 'panel_' . $i, array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Content', 'twentyseventeen' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Select pages to feature in each area from the dropdowns. Add an image to a section by setting a featured image in the page editor. Empty sections will not be displayed.', 'twentyseventeen' ) ),
			'section'        => 'theme_options',
			'type'           => 'dropdown-pages',
			'allow_addition' => true,
			'active_callback' => 'twentyseventeen_is_static_front_page',
		) );

		$wp_customize->selective_refresh->add_partial( 'panel_' . $i, array(
			'selector'            => '#panel' . $i,
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );
		$wp_customize->add_setting( 'panel_' . $i.'_Headline_Use_Title', array(
			'default'           => 'false'
		) );
		$wp_customize->add_control( 'panel_' . $i . '_Headline_Use_Title', array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Use Title', 'twentyseventeen' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Use the page title for the headline', 'twentyseventeen' ) ),
			'section'        => 'theme_options',
			'type'           => 'checkbox',
			'active_callback' => 'twentyseventeen_is_static_front_page',
		) );
		$wp_customize->selective_refresh->add_partial( 'panel_' . $i.'_Headline_Use_Title', array(
			'selector'            => '#panel' . $i.'_Headline_Use_Title',
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );
	
		$wp_customize->add_setting( 'panel_' . $i.'_Headline', array(
			'default'           => '',
			'sanitizer_callback' => 'wp_kses_post'
		) );
		$wp_customize->add_control( 'panel_' . $i . '_Headline', array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Headline', 'twentyseventeen' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Include information you would like displayed in the middle of the image. Blank entires will be ignored', 'twentyseventeen' ) ),
			'section'        => 'theme_options',
			'type'           => 'text',
			'active_callback' => 'twentyseventeen_is_static_front_page',
		) );
		$wp_customize->selective_refresh->add_partial( 'panel_' . $i.'_Headline', array(
			'selector'            => '#panel' . $i.'_Headline',
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );
		$wp_customize->add_setting( 'panel_' . $i.'_Headline_Font', array(
			'default'           => '',
			'sanitizer_callback' => 'wp_filter_nohtml_kses'
		) );
		$wp_customize->add_control( 'panel_' . $i . '_Headline_Font', array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Headline Font', 'twentyseventeen' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Change the font of the headline. Enter the name of a google font', 'twentyseventeen' ) ),
			'section'        => 'theme_options',
			'type'           => 'text',
			'active_callback' => 'twentyseventeen_is_static_front_page',
		) );
		$wp_customize->selective_refresh->add_partial( 'panel_' . $i.'_Headline_Font', array(
			'selector'            => '#panel' . $i.'_Headline_Font',
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );
		$wp_customize->add_setting( 'panel_' . $i.'_Headline_style', array(
			'default'           => '',
			'sanitizer_callback' => 'wp_strip_all_tags'
		) );
		$wp_customize->add_control( 'panel_' . $i . '_Headline_style', array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Headline Style', 'twentyseventeen' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Change the font of the headline. Enter the name of a google font. If you are using a different font provider please provide full url (i.e. include http:// or https://)', 'twentyseventeen' ) ),
			'section'        => 'theme_options',
			'type'           => 'text',
			'active_callback' => 'twentyseventeen_is_static_front_page',
		) );
		$wp_customize->selective_refresh->add_partial( 'panel_' . $i.'_Headline_style', array(
			'selector'            => '#panel' . $i.'_Headline_style',
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );
		$wp_customize->add_setting( 'panel_' . $i.'_SubHeadline', array(
			'default'           => '',
			'sanitizer_callback' => 'wp_kses_post'
		) );
		$wp_customize->add_control( 'panel_' . $i . '_SubHeadline', array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Sub Headline', 'twentyseventeen' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Include information you would like displayed in the middle of the image. Blank entires will be ignored', 'twentyseventeen' ) ),
			'section'        => 'theme_options',
			'type'           => 'text',
			'active_callback' => 'twentyseventeen_is_static_front_page',
		) );
		$wp_customize->selective_refresh->add_partial( 'panel_' . $i.'_SubHeadline', array(
			'selector'            => '#panel' . $i.'_SubHeadline',
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );

				$wp_customize->add_setting( 'panel_' . $i.'_SubHeadline_Font', array(
			'default'           => '',
			'sanitizer_callback' => 'wp_filter_nohtml_kses'
		) );
		$wp_customize->add_control( 'panel_' . $i . '_SubHeadline_Font', array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d SubHeadline Font', 'twentyseventeen' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Change the font of the Subheadline. Enter the name of a google font', 'twentyseventeen' ) ),
			'section'        => 'theme_options',
			'type'           => 'text',
			'active_callback' => 'twentyseventeen_is_static_front_page',
		) );
		$wp_customize->selective_refresh->add_partial( 'panel_' . $i.'_SubHeadline_Font', array(
			'selector'            => '#panel' . $i.'_SubHeadline_Font',
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );
		$wp_customize->add_setting( 'panel_' . $i.'_SubHeadline_style', array(
			'default'           => '',
			'sanitizer_callback' => 'wp_strip_all_tags'
		) );
		$wp_customize->add_control( 'panel_' . $i . '_SubHeadline_style', array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d SubHeadline Style', 'twentyseventeen' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Change the font of the Subheadline. Enter the name of a google font', 'twentyseventeen' ) ),
			'section'        => 'theme_options',
			'type'           => 'text',
			'active_callback' => 'twentyseventeen_is_static_front_page',
		) );
		$wp_customize->selective_refresh->add_partial( 'panel_' . $i.'_SubHeadline_style', array(
			'selector'            => '#panel' . $i.'_SubHeadline_style',
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );
		$wp_customize->add_setting( 'panel_' . $i.'_Show_Title', array(
			'default'           => 'false',
			
		) );
		$wp_customize->add_control( 'panel_' . $i . '_Show_Title', array(
			/* translators: %d is the front page section number */
			'label'          => sprintf( __( 'Front Page Section %d Show Title', 'twentyseventeen' ), $i ),
			'description'    => ( 1 !== $i ? '' : __( 'Show text over the image.', 'twentyseventeen' ) ),
			'section'        => 'theme_options',
			'type'           => 'checkbox',
			'active_callback' => 'twentyseventeen_is_static_front_page',
		) );
		$wp_customize->selective_refresh->add_partial( 'panel_' . $i.'_Show_Title', array(
			'selector'            => '#panel' . $i.'_Show_Title',
			'render_callback'     => 'twentyseventeen_front_page_section',
			'container_inclusive' => true,
		) );
	}
}
remove_action( 'customize_register', 'twentyseventeen_customize_register' );
add_action( 'customize_register', 'twentyseventeen_child_customize_register' );

