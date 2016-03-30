<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

/**
 * Trip stop field class.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2014, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @package Config\Trips
 * @author The Happy Bit <thehappybit@gmail.com>
 * @copyright Copyright 2014, Andrea Gandino & Simone Maranzana
 * @link http://
 * @since The Happy Framework v 2.0.2
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if( ! class_exists('THB_TripStopField') ) {
	class THB_TripStopField extends THB_Field {
		/**
		 * Constructor
		 *
		 * @param string $name The field name.
		 * @param integer $context The field context.
		 **/
		public function __construct( $name, $context = null )
		{
			parent::__construct( $name, 'voyager_trip_stop', $context );
		}

		/**
		 * Pre-process the field data before saving.
		 *
		 * @param mixed $data The field POST data.
		 * @return mixed
		 */
		public function preProcessData( $data )
		{
			return $data;
		}
	}
}

/**
 * Latitude & longitude field class.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2014, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @package Config\Trips
 * @author The Happy Bit <thehappybit@gmail.com>
 * @copyright Copyright 2014, Andrea Gandino & Simone Maranzana
 * @link http://
 * @since The Happy Framework v 2.0.2
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if( ! class_exists('THB_VoyagerLatLongField') ) {
	class THB_VoyagerLatLongField extends THB_Field {
		/**
		 * Constructor
		 *
		 * @param string $name The field name.
		 * @param integer $context The field context.
		 **/
		public function __construct( $name, $context = null )
		{
			parent::__construct( $name, 'voyager_latlong', $context );
		}
	}
}

$thb_trip_stop_modal = new THB_Modal( __( 'Trip stop', 'thb_text_domain' ), 'voyager_trip_stop' );
	$thb_modal_tab = $thb_trip_stop_modal->createTab( __( 'Data', 'thb_text_domain' ), 'voyager_trip_data' );
	$thb_modal_container = $thb_modal_tab->createContainer( '', 'voyager_trip_stop_container' );

		$thb_field = new THB_TextField( 'label' );
		$thb_field->setLabel( __( 'Label', 'thb_text_domain' ) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_VoyagerLatLongField( 'latlong' );
		$thb_field->setLabel( __( 'Latitude & longitude', 'thb_text_domain' ) );
		$thb_field->setHelp( __( 'Click, search or drag the marker on the map.', 'thb_text_domain' ) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_TextareaField( 'description' );
		$thb_field->setLabel( __( 'Description', 'thb_text_domain' ) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_UploadField( 'marker' );
		$thb_field->setLabel( __( 'Marker icon', 'thb_text_domain' ) );
		$thb_field->setHelp( __( 'This marker will be used in the map.', 'thb_text_domain' ) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_SelectField( 'show_on_map' );
		$thb_field->setLabel( __( 'Show on map', 'thb_text_domain' ) );
		$thb_field->setOptions( array(
			'1' => __( 'Yes', 'thb_text_domain' ),
			'0' => __( 'No', 'thb_text_domain' )
		) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_SelectField( 'show_in_page' );
		$thb_field->setLabel( __( 'Show in page', 'thb_text_domain' ) );
		$thb_field->setOptions( array(
			'1' => __( 'Yes', 'thb_text_domain' ),
			'0' => __( 'No', 'thb_text_domain' )
		) );
		$thb_modal_container->addField( $thb_field );

	$thb_modal_tab = $thb_trip_stop_modal->createTab( __( 'Pictures', 'thb_text_domain' ), 'voyager_trip_stop_pictures' );
	$thb_modal_container = $thb_modal_tab->createDuplicableContainer( __( 'Pictures', 'thb_text_domain' ), 'voyager_trip_stop_container_pictures' );
	$thb_modal_container->setSortable();

		$thb_modal_container->addControl( __('Add', 'thb_text_domain'), 'add_image', '', array(
			'action' => 'thb_add_multiple_slides',
			'title' => __('Add pictures', 'thb_text_domain')
		) );
		$thb_modal_container->setIntroText( __( '<strong>Note</strong>: These images will be used when a "Mosaic" layout is selected.', 'thb_text_domain' ) );

		$slide_field = new THB_SlideField( 'voyager_trip_stop_slide' );
		$slide_field->setLabel( __('Picture', 'thb_text_domain') );
		$slide_field->getModal( 'edit_slide_image' )->getContainer( 'edit_slide_image_container' )->removeField( 'class' );
		$thb_modal_container->setField( $slide_field );

	$thb_modal_tab = $thb_trip_stop_modal->createTab( __( 'Layout', 'thb_text_domain' ), 'voyager_trip_layout' );
	$thb_modal_container = $thb_modal_tab->createContainer( '', 'voyager_trip_stop_container_layout' );

		$thb_field = new THB_SelectField( 'layout' );
		$thb_field->setLabel( __( 'Layout', 'thb_text_domain' ) );
		$thb_field->setOptions( array(
			'full'          => __( 'Full screen image', 'thb_text_domain' ),
			'full-center'   => __( 'Full screen image, centered text', 'thb_text_domain' ),
			'mosaic-center' => __( 'Mosaic centered', 'thb_text_domain' ),
			'mosaic-left'   => __( 'Mosaic left', 'thb_text_domain' ),
			'mosaic-right'  => __( 'Mosaic right', 'thb_text_domain' ),
			'left'          => __( 'Image on the left', 'thb_text_domain' ),
			'right'         => __( 'Image on the right', 'thb_text_domain' ),
		) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_CheckboxField( 'fit_viewport' );
		$thb_field->setLabel( __( 'Fit the viewport height', 'thb_text_domain' ) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_CheckboxField( 'hide_posts' );
		$thb_field->setLabel( __( 'Hide posts', 'thb_text_domain' ) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_CheckboxField( 'show_gallery' );
		$thb_field->setLabel( __( 'View gallery button', 'thb_text_domain' ) );
		$thb_field->setHelp( __( 'Show a button that will display images taken from posts belonging to the trip in a lightbox (if enabled).', 'thb_text_domain' ) );
		$thb_modal_container->addField( $thb_field );

	$thb_modal_container = $thb_modal_tab->createContainer( __( 'Mosaic options', 'thb_text_domain' ), 'voyager_trip_stop_container_layout' );

		$thb_field = new THB_TextField( 'mosaic_module' );
		$thb_field->setLabel( __( 'Module', 'thb_text_domain' ) );
		$thb_field->setHelp( __( 'E.g. 231 will produce three rows, the 1st with two images, the 2nd with three, etc.', 'thb_text_domain' ) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_NumberField( 'mosaic_gutter' );
		$thb_field->setLabel( __( 'Gutter', 'thb_text_domain' ) );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_SelectField( 'mosaic_image_size' );
		$thb_field->setLabel( __( 'Image size', 'thb_text_domain' ) );
		$thb_field->setOptions( array(
			'large'     => __( 'Large', 'thb_text_domain' ),
			'medium'    => __( 'Medium', 'thb_text_domain' ),
			'thumbnail' => __( 'Small', 'thb_text_domain' ),
			'full'      => __( 'Full', 'thb_text_domain' ),
		) );
		$thb_modal_container->addField( $thb_field );

	$thb_modal_tab = $thb_trip_stop_modal->createTab( __( 'Background', 'thb_text_domain' ), 'voyager_trip_background' );
	$thb_modal_container = $thb_modal_tab->createContainer( '', 'voyager_trip_stop_container_background' );

		$thb_field = new THB_BackgroundField( 'background' );
		$thb_field->setLabel( __( 'Background', 'thb_text_domain' ) );
		$thb_field->addClass( 'full' );
		$thb_modal_container->addField( $thb_field );

		$thb_field = new THB_SelectField( 'background_appearance' );
		$thb_field->setLabel( __( 'Appearance', 'thb_text_domain' ) );
		$thb_field->setOptions( array(
			'relative' => __( 'Regular', 'thb_text_domain' ),
			'parallax' => __( 'Parallax', 'thb_text_domain' )
		) );
		$thb_modal_container->addField( $thb_field );

thb_theme()->getAdmin()->addModal( $thb_trip_stop_modal );