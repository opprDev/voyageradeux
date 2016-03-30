<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

/**
 * Theme functionalities.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2012, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @package Config
 * @author The Happy Bit <thehappybit@gmail.com>
 * @copyright Copyright 2012, Andrea Gandino & Simone Maranzana
 * @link http://
 * @since The Happy Framework v 1.0
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

require_once dirname( __FILE__ ) . '/helpers.php';
require_once dirname( __FILE__ ) . '/trips/module.php';
require_once dirname( __FILE__ ) . '/lib/class.typekit.php';

$thb_theme = thb_theme();

$thb_theme->loadModule('backpack', array(
	'megamenu' => array(),
	'layout' => array(
		'grid_columns' => array(
			'3' => array( 'fixed' => 'grid-large-cropped', 'variable' => 'grid-large' ),
			'4' => array( 'fixed' => 'grid-large-cropped', 'variable' => 'grid-large' ),
			'5' => array( 'fixed' => 'grid-small-cropped', 'variable' => 'grid-small' )
		),
		'grid_gutter' => array(
			'no-gutter' => __( 'No gutter', 'thb_text_domain' ),
			'small'     => __( 'Small', 'thb_text_domain' ),
			'normal'    => __( 'Normal', 'thb_text_domain' )
		),
		'options_logo_position' => array(
			'logo-left'   => __( 'Left', 'thb_text_domain' ),
			'logo-right'  => __( 'Right', 'thb_text_domain' )
		),
		'meta_options_pageheader_disable' => thb_get_theme_templates()
	),
	'slideshow' => array(
		'templates' => array(
			'default',
			'shop',
			'single.php',
			'single-trip.php',
			'template-portfolio.php',
			'template-photogallery.php',
			'template-blog.php',
			'template-blog-grid.php'
		)
	),
	'builder' => array(
		'templates' => array(
			'default',
			'shop',
			'template-blog.php',
			'template-blank.php',
			'template-photogallery.php',
			'single-works.php',
			'single-trip.php',
			'single.php'
		)
	),
	'blog' => array(
		'enable_author_block' => true,
		'sidebars' => true,
		'templates' => array(
			'template-blog.php',
			'template-blog-grid.php'
		),
		'builder_blog_layouts' => array(
			'classic'  => __( 'Classic', 'thb_text_domain' ),
			'masonry'  => __( 'Masonry', 'thb_text_domain' ),
		),
		'subtitle' => true
	),
	'like' => array(),
	'sidebars' => array(
		'templates' => array(
			'default',
			'single.php',
			'template-blog.php',
			'template-blank.php',
		)
	),
	'photogallery' => array(
		'templates' => array(
			'template-photogallery.php'
		),
		'templates_columns' => array(
			'template-photogallery.php' => array(
				'3' => '3',
				'4' => '4',
				'5' => '5'
			)
		),
		'builder_block_columns' => array(
			'3' => '3',
			'4' => '4',
			'5' => '5'
		)
	),
	'general' => array(
		'builder_text_box_layout_styles' => array(
			'layout-style-a' => __('Standard header', 'thb_text_domain'),
			'layout-style-b' => __('Big header', 'thb_text_domain'),
			'layout-style-c' => __('Medium header', 'thb_text_domain'),
			'layout-style-d' => __('Medium header with line', 'thb_text_domain'),
			'layout-style-e' => __('Small header', 'thb_text_domain')
		),
		'analytics' => false
	)
));

$thb_theme->loadModule( 'woocommerce', array(
	'skin' => true,
	'sidebar_product' => false
) );

$thb_theme->loadModule( 'bbpress', array(
	'skin' => true
) );

$thb_theme->loadModule( 'gravityforms' );

if( ! function_exists( 'thb_voyager_portfolio_config' ) ) {
	/**
	 * Portfolio configuration.
	 *
	 * @param array $config
	 * @return array
	 */
	function thb_voyager_portfolio_config( $config ) {
		$config['templates'] = array(
			'template-portfolio.php'
		);
		$config['ajax'] = array('template-portfolio.php');
		$config['work_slides'] = true;
		$config['single'] = false;
		$config['work_details'] = 'keyvalue';
		$config['grid_templates'] = array('template-portfolio.php');
		$config['grid_templates_columns'] = array(
			'template-portfolio.php' => array(
				'3' => '3',
				'4' => '4',
				'5' => '5'
			)
		);
		$config['grid_builder_columns'] = array(
			'3' => '3',
			'4' => '4',
			'5' => '5'
		);

		$config['builder_portfolio_layouts'] = array(
			'thb-portfolio-grid-a' => get_template_directory_uri() . '/css/i/options/portfolio-grid-a.png',
			'thb-portfolio-grid-b' => get_template_directory_uri() . '/css/i/options/portfolio-grid-b.png',
			'thb-portfolio-grid-c' => get_template_directory_uri() . '/css/i/options/portfolio-grid-c.png'
		);

		return $config;
	}

	add_filter( 'thb_portfolio_config', 'thb_voyager_portfolio_config' );
}

if( ! function_exists( 'thb_voyager_slide_content_data' ) ) {
	/**
	 * Add the skin class to slideshow slides.
	 *
	 * @param array $slide_content_data
	 * @return array
	 */
	function thb_voyager_slide_content_data( $slide_content_data ) {
		$overlay_color = $slide_content_data['slide']['overlay_color'];

		if ( ! empty( $overlay_color ) ) {
			$slide_content_data['slide_attrs']['class'] .= ' thb-skin-' . thb_color_get_opposite_skin( $overlay_color );
		}

		return $slide_content_data;
	}

	add_action( 'thb_slide_content_data', 'thb_voyager_slide_content_data' );
}

if( !function_exists('thb_extend_slideshow_slides') ) {
	/**
	 * Add the required fields to the slideshow modals.
	 *
	 * @param THB_SlideField $slide
	 * @return THB_SlideField
	 */
	function thb_extend_slideshow_slides( $slide ) {
		$thb_modal = $slide->getModal( 'edit_slide_image' );

		$thb_modal_container = $thb_modal->getContainer( 'edit_slide_image_container' );

			$thb_field = new THB_TextareaField( 'heading' );
			$thb_field->setLabel( __( 'Heading', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field, 0 );

		$thb_modal_tab = $thb_modal->createTab( __( 'Overlay', 'thb_text_domain' ), 'overlay_tab' );
		$thb_modal_container = $thb_modal_tab->createContainer( '', 'overlay_tab_container' );

			$thb_field = new THB_CheckboxField( 'overlay_display' );
			$thb_field->setLabel( __( 'Overlay display', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field );

			$thb_field = new THB_ColorField( 'overlay_color' );
			$thb_field->setLabel( __( 'Overlay color', 'thb_text_domain' ) );
			$thb_field->setHelp( 'The color of the overlay (even if not enabled) determines the skin used for texts (e.g. a dark color automatically generates light text).' );
			$thb_modal_container->addField( $thb_field );

			$thb_field = new THB_NumberField( 'overlay_opacity' );
			$thb_field->setMin( 0 );
			$thb_field->setMax( 1 );
			$thb_field->setStep( 0.05 );
			$thb_field->setLabel( __( 'Overlay opacity', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field );

		$thb_modal_tab = $thb_modal->createTab( __( 'Caption & call-to-action', 'thb_text_domain' ), 'caption_tab' );
		$thb_modal_container = $thb_modal_tab->createContainer( '', 'caption_tab_container' );

			$thb_field = new THB_SelectField( 'caption_position' );
				$thb_field->setLabel( __( 'Caption position', 'thb_text_domain' ) );
				$thb_field->setDefault( 'caption-bottom' );
				$thb_field->setOptions(array(
					'caption-bottom' => __('Bottom', 'thb_text_domain'),
					'caption-top'    => __('Top', 'thb_text_domain')
				));
			$thb_modal_container->addField( $thb_field );

			$thb_field = new THB_SelectField( 'caption_alignment' );
			$thb_field->setLabel( __( 'Caption alignment', 'thb_text_domain' ) );
			$thb_field->setDefault( 'thb-caption-left' );
			$thb_field->setOptions(array(
				'thb-caption-left'   => __('Left', 'thb_text_domain'),
				'thb-caption-center' => __('Center', 'thb_text_domain'),
				'thb-caption-right'  => __('Right', 'thb_text_domain')
			));
			$thb_modal_container->addField($thb_field );

			$thb_field = new THB_TextField( 'call_to_label' );
			$thb_field->setLabel( __( 'Call to action label', 'thb_text_domain' ) );
			$thb_field->setHelp( __('The call to action button label.', 'thb_text_domain'));
			$thb_modal_container->addField( $thb_field );

			$thb_field = new THB_TextField( 'call_to_url' );
			$thb_field->setLabel( __( 'Call to action URL', 'thb_text_domain' ) );
			$thb_field->setHelp( __('The call to action button URL. You can use a manual URL http:// or a post or page ID.', 'thb_text_domain'));
			$thb_modal_container->addField( $thb_field );

			$thb_field = new THB_CheckboxField( 'call_to_url_target_blank' );
			$thb_field->setLabel( __( 'Call to action opens in a new tab/window', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field );

		/**
		 * Video
		 */

		$thb_modal = $slide->getModal( 'edit_slide_video' );
		$thb_modal_container = $thb_modal->getContainer( 'edit_slide_video_container' );

			$thb_modal_container->setIntroText( __( '<strong>Recommended setting</strong>: video works better if the slideshow effect is set to "slide".', 'thb_text_domain' ) );

			$thb_field = new THB_TextareaField( 'heading' );
			$thb_field->setLabel( __( 'Heading', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field, 1 );

			$thb_field = new THB_UploadField( 'poster_image' );
			$thb_field->setLabel( __( 'Poster image', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'Cover image for the video, also used as mobile fallback.', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field, -1 );

			$thb_field = new THB_CheckboxField( 'hide_text_when_playing' );
			$thb_field->setLabel( __( 'Hide text when playing', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field, -1 );

			$thb_field = new THB_CheckboxField( 'hide_controls' );
			$thb_field->setLabel( __( 'Hide controls', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field, -1 );

			$thb_field = new THB_CheckboxField( 'mute_video' );
			$thb_field->setLabel( __( 'Mute video', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field, -1 );

			// $thb_field = new THB_CheckboxField( 'show_video_volume_controls' );
			// $thb_field->setLabel( __( 'Show volume controls', 'thb_text_domain' ) );
			// $thb_modal_container->addField( $thb_field, -1 );

		$thb_modal_tab = $thb_modal->createTab( __( 'Overlay', 'thb_text_domain' ), 'overlay_tab' );
		$thb_modal_container = $thb_modal_tab->createContainer( '', 'overlay_tab_container' );

			$thb_field = new THB_CheckboxField( 'overlay_display' );
			$thb_field->setLabel( __( 'Overlay display', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field );

			$thb_field = new THB_ColorField( 'overlay_color' );
			$thb_field->setLabel( __( 'Overlay color', 'thb_text_domain' ) );
			$thb_field->setHelp( 'The color of the overlay (even if not enabled) determines the skin used for texts (e.g. a dark color automatically generates light text).' );
			$thb_modal_container->addField( $thb_field );

			$thb_field = new THB_NumberField( 'overlay_opacity' );
			$thb_field->setMin( 0 );
			$thb_field->setMax( 1 );
			$thb_field->setStep( 0.05 );
			$thb_field->setLabel( __( 'Overlay opacity', 'thb_text_domain' ) );
			$thb_modal_container->addField( $thb_field );

		$thb_modal_tab = $thb_modal->createTab( __( 'Caption & call-to-action', 'thb_text_domain' ), 'caption_tab' );
		$thb_modal_container = $thb_modal_tab->createContainer( '', 'caption_tab_container' );

			$thb_field = new THB_SelectField( 'caption_position' );
				$thb_field->setLabel( __( 'Caption position', 'thb_text_domain' ) );
				$thb_field->setDefault( 'caption-bottom' );
				$thb_field->setOptions(array(
					'caption-top'    => __('Top', 'thb_text_domain'),
					'caption-bottom' => __('Bottom', 'thb_text_domain')
				));
			$thb_modal_container->addField( $thb_field );

			$thb_field = new THB_SelectField( 'caption_alignment' );
			$thb_field->setLabel( __( 'Caption alignment', 'thb_text_domain' ) );
			$thb_field->setDefault( 'thb-caption-left' );
			$thb_field->setOptions(array(
				'thb-caption-left'   => __('Left', 'thb_text_domain'),
				'thb-caption-center' => __('Center', 'thb_text_domain'),
				'thb-caption-right'  => __('Right', 'thb_text_domain')
			));
			$thb_modal_container->addField($thb_field );

			$thb_field = new THB_TextField( 'call_to_label' );
			$thb_field->setLabel( __( 'Call to action label', 'thb_text_domain' ) );
			$thb_field->setHelp( __('The call to action button label.', 'thb_text_domain'));
			$thb_modal_container->addField( $thb_field );

			$thb_field = new THB_TextField( 'call_to_url' );
			$thb_field->setLabel( __( 'Call to action URL', 'thb_text_domain' ) );
			$thb_field->setHelp( __('The call to action button URL. You can use a manual URL http:// or a post or page ID.', 'thb_text_domain'));
			$thb_modal_container->addField( $thb_field );

		return $slide;
	}

	add_filter( 'thb_slideshow_slide', 'thb_extend_slideshow_slides' );
}

/**
 * Theme style customization
 */
require_once "customization.php";