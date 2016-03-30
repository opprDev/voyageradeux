<?php

/**
 * Trip custom post type.
 *
 * ---
 *
 * The Happy Framework: WordPress Development Framework
 * Copyright 2012, Andrea Gandino & Simone Maranzana
 *
 * Licensed under The MIT License
 * Redistribuitions of files must retain the above copyright notice.
 *
 * @author The Happy Bit <thehappybit@gmail.com>
 * @copyright Copyright 2012, Andrea Gandino & Simone Maranzana
 * @link http://
 * @since The Happy Framework v 2.0.2
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

function thb_trip_post_type() {

	/**
	 * The post type labels.
	 *
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	$thb_trips_labels = array(
		'name'               => __('Trips', 'thb_text_domain'),
		'singular_name'      => __('Trip', 'thb_text_domain'),
		'add_new'            => __('Add new', 'thb_text_domain'),
		'add_new_item'       => __('Add new Trip', 'thb_text_domain'),
		'edit'               => __('Edit', 'thb_text_domain'),
		'edit_item'          => __('Edit Trip', 'thb_text_domain'),
		'new_item'           => __('New Trip', 'thb_text_domain'),
		'view'               => __('View Trip', 'thb_text_domain'),
		'view_item'          => __('View Trip', 'thb_text_domain'),
		'search_items'       => __('Search Trips', 'thb_text_domain'),
		'not_found'          => __('No Trips found', 'thb_text_domain'),
		'not_found_in_trash' => __('No Trips found in Trash', 'thb_text_domain'),
		'parent'             => __('Parent Trip', 'thb_text_domain')
	);

	/**
	 * The post type arguments.
	 *
	 * @see http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	$options_slug = thb_get_option( 'trips_url_slug' );
	$slug = ! empty( $options_slug ) ? $options_slug : 'trip';

	$thb_trips_args = array(
		'labels'            => $thb_trips_labels,
		'public'            => true,
		'show_ui'           => true,
		'capability_type'   => 'post',
		'hierarchical'      => false,
		'menu_icon' 		=> '',
		'rewrite'           => array( 'slug' => $slug, 'with_front' => true ),
		'query_var'         => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'thumbnail', 'excerpt', 'editor', 'comments' )
	);

	$thb_trips_args = apply_filters( 'thb_trips_args', $thb_trips_args );

	/**
	 * Create the post type object.
	 */
	register_post_type( 'trip', $thb_trips_args );

	/**
	 * Add the post type to the theme instance.
	 */
	$thb_trips = new THB_PostType( 'trip', $thb_trips_args );
	thb_theme()->addPostType( $thb_trips );

	/**
	 * Create the post type taxonomy for Trip categories.
	 */
	$thb_trip_categories_args = array(
		'hierarchical'   => true,
		'labels' => array(
			'name'              => __('Trip Categories', 'thb_text_domain' ),
			'singular_name'     => __('Trip Category', 'thb_text_domain' ),
			'search_items'      => __( 'Search Trip Categories', 'thb_text_domain' ),
			'all_items'         => __( 'All Trip Categories', 'thb_text_domain' ),
			'parent_item'       => __( 'Parent Trip Category', 'thb_text_domain' ),
			'parent_item_colon' => __( 'Parent Trip Category:', 'thb_text_domain' ),
			'edit_item'         => __( 'Edit Trip Category', 'thb_text_domain' ),
			'update_item'       => __( 'Update Trip Category', 'thb_text_domain' ),
			'add_new_item'      => __( 'Add New Trip Category', 'thb_text_domain' ),
			'new_item_name'     => __( 'New Trip Category Name', 'thb_text_domain' ),
			'menu_name'         => __( 'Trip Category', 'thb_text_domain' )
		),
		'rewrite'        => true,
		'query_var'      => true
	);

	register_taxonomy( 'trip_categories', 'trip', $thb_trip_categories_args );

	$thb_trips_taxonomy = new THB_Taxonomy( 'trip_categories', $thb_trip_categories_args );
	$thb_trips->addTaxonomy( $thb_trips_taxonomy );
}

function thb_trip_post_type_metabox() {
	/**
	 * Metaboxes.
	 */
	// $thb_metabox = new THB_Metabox( __( 'Trip details', 'thb_text_domain' ), 'trip_details' );
	// $thb_metabox->setPriority( 'high' );

	$thb_trips = thb_theme()->getPostType( 'trip' );
	$thb_metabox = $thb_trips->getMetabox('layout');

		$thb_container = $thb_metabox->getContainer( 'layout_container' );
			$thb_field = new THB_CheckboxField( 'hide_stops' );
			$thb_field->setLabel( __( 'Hide stops', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'Hide stops from page, this could be useful if you want to only generate a map and use the page builder to design your page.', 'thb_text_domain' ) );
			$thb_container->addField( $thb_field , -1 );

			$thb_field = new THB_CheckboxField( 'show_map_in_page' );
			$thb_field->setLabel( __( 'Show map in page', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'Show map in page also if you\'ve an extended page header layout.', 'thb_text_domain' ) );
			$thb_container->addField( $thb_field , -1 );

		$thb_container = $thb_metabox->createContainer( '', 'trip_details_container' );
			$thb_field = new THB_TextField( 'subtitle' );
			$thb_field->setLabel( __( 'Subtitle', 'thb_text_domain' ) );
			$thb_container->addField( $thb_field );

		$thb_tab = $thb_metabox->createTab( __( 'Trip map', 'thb_text_domain' ), 'trip_map' );
		$thb_tab->addSeparator();
		$thb_tab->setIcon( 'location-alt' );
		$thb_container = $thb_tab->createContainer( '', 'trip_map_container' );

			$thb_field = new THB_SelectField( 'map_type' );
			$thb_field->setLabel( __( 'Map type', 'thb_text_domain' ) );
			$thb_field->setOptions( array(
				'ROADMAP'   => __( 'Roadmap', 'thb_text_domain' ),
				'SATELLITE' => __( 'Satellite', 'thb_text_domain' ),
				'HYBRID'    => __( 'Hybrid', 'thb_text_domain' ),
				'TERRAIN'   => __( 'Terrain', 'thb_text_domain' ),
			) );
			$thb_container->addField( $thb_field );

			$thb_field = new THB_TextareaField( 'map_style' );
			$thb_field->setLabel( __( 'Map style', 'thb_text_domain' ) );
			$thb_field->setHelp( sprintf( __( 'You can get more map styles <a target="_blank" href="%s">here</a>.', 'thb_text_domain' ), 'http://snazzymaps.com/' ) );
			$thb_container->addField( $thb_field );

			$thb_field = new THB_VoyagerLatLongField( 'map_center' );
			$thb_field->setLabel( __( 'Map center', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'Coordinates for the map center. If empty, the first marker will be the center of the map.<br><br><strong>Note:</strong> This is not a preview of how the map is going to be displayed.', 'thb_text_domain' ) );
			$thb_container->addField( $thb_field );

			$thb_field = new THB_CheckboxField( 'map_connected_dots' );
			$thb_field->setLabel( __( 'Connect dots on the map', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'Connecting the dots will create the effect of an itinerary.', 'thb_text_domain' ) );
			$thb_container->addField( $thb_field );

			$thb_field = new THB_CheckboxField( 'map_geodesic' );
			$thb_field->setLabel( __( 'Geodesic map', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'Lines connecting the dots will follow the earth\'s curvature.', 'thb_text_domain' ) );
			$thb_container->addField( $thb_field );

			$thb_field = new THB_ColorField( 'map_strokeColor' );
			$thb_field->setLabel( __( 'Lines color', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'The color of the lines connecting the dots.', 'thb_text_domain' ) );
			$thb_container->addField( $thb_field );

			$thb_field = new THB_NumberField( 'map_strokeOpacity' );
			$thb_field->setLabel( __( 'Lines opacity', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'Opacity of the lines connecting the dots.', 'thb_text_domain' ) );
			$thb_field->setMin( '0' );
			$thb_field->setMax( '1' );
			$thb_field->setStep( '0.1' );
			$thb_container->addField( $thb_field );

			$thb_field = new THB_NumberField( 'map_strokeWeight' );
			$thb_field->setLabel( __( 'Lines width', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'Width of the lines connecting the dots.', 'thb_text_domain' ) );
			$thb_field->setMin( '0' );
			$thb_container->addField( $thb_field );

			$thb_field = new THB_NumberField( 'map_zoom' );
			$thb_field->setLabel( __( 'Zoom level', 'thb_text_domain' ) );
			$thb_field->setHelp( __( 'Defaults to 12.', 'thb_text_domain' ) );
			$thb_field->setMin( '0' );
			$thb_field->setMax( '19' );
			$thb_container->addField( $thb_field );

		$thb_tab = $thb_metabox->createTab( __( 'Trip stops', 'thb_text_domain' ), 'trip_stops' );
		$thb_tab->setIcon( 'location' );
		$thb_container = $thb_tab->createDuplicableContainer( '', 'trip_stops_container' );
			$thb_container->setSortable( true, true );
			$thb_container->addControl( __( 'Add new stop', 'thb_text_domain' ), '', '', array(
				'action' => 'thb_voyager_trip_add_stop'
			) );

			$thb_field = new THB_TripStopField( 'stop' );
			$thb_field->setLabel( '' );
			$thb_container->setField( $thb_field );

	$thb_trips->addMetabox( $thb_metabox );

	/**
	 * Posts integration.
	 */
	$thb_posts = thb_theme()->getPostType( 'post' );

	// $thb_metabox = new THB_Metabox( __( 'Trip', 'thb_text_domain' ), 'trip' );
	$thb_metabox = $thb_posts->getMetabox( 'layout' );
	$thb_tab = $thb_metabox->createTab( __( 'Trip', 'thb_text_domain' ), 'trip' );
	$thb_tab->setIcon( 'location' );

		$thb_container = $thb_tab->createContainer( '', 'trip_container' );
			$thb_field = new THB_SelectField( 'trip' );
			$thb_field->setLabel( __( 'Trip', 'thb_text_domain' ) );
			$thb_field->setOptions( thb_get_post_type_for_select( 'trip' ) );
			$thb_field->setCallback( 'thb_sync_trip_stops' );
			$thb_container->addField( $thb_field );

			$post_id = thb_input_get( 'post', 'absint', 0 );
			$trip_id = thb_get_post_meta( $post_id, 'trip' );
			$trip_stops = array( '', '--' );

			if ( ! empty( $trip_id ) ) {
				$trip_stops = thb_trip_get_stops_for_select( $trip_id );
			}

			$thb_field = new THB_SelectField( 'trip_stop' );
			$thb_field->setLabel( __( 'Trip stop', 'thb_text_domain' ) );
			$thb_field->setOptions( $trip_stops );
			$thb_field->setInvisibleIfEmpty( false );
			$thb_container->addField( $thb_field );
}

/**
 * Localize admin scripts.
 */
function thb_trip_admin_localize_scripts() {
	wp_localize_script( 'jquery', 'voyager_trips', thb_get_trips() );
}

/**
 * Localize frontend scripts.
 */
function thb_trip_localize_scripts() {
	if ( ! is_singular( 'trip' ) ) {
		return;
	}

	$zoom = thb_get_post_meta( get_the_ID(), 'map_zoom' );
	$type = thb_get_post_meta( get_the_ID(), 'map_type' );
	$styles = thb_get_post_meta( get_the_ID(), 'map_style' );
	$center = thb_get_post_meta( get_the_ID(), 'map_center' );
	$itinerary = (int) thb_get_post_meta( get_the_ID(), 'map_connected_dots' );
	$geodesic = (int) thb_get_post_meta( get_the_ID(), 'map_geodesic' );
	$strokeColor = thb_get_post_meta( get_the_ID(), 'map_strokeColor' );
	$strokeOpacity = thb_get_post_meta( get_the_ID(), 'map_strokeOpacity' );
	$strokeWeight = thb_get_post_meta( get_the_ID(), 'map_strokeWeight' );

	if ( empty( $zoom ) ) {
		$zoom = 12;
	}

	if ( empty( $styles ) ) {
		$styles = json_encode( array() );
	}

	$center = str_replace( ' ', '', $center );

	$center_array = array();

	if ( ! empty( $center ) ) {
		$center_array = explode( ',', $center );
	}

	if ( empty( $strokeColor ) ) {
		$strokeColor = '#FFFFFF';
	}

	if ( empty( $strokeOpacity ) ) {
		$strokeOpacity = '1';
	}

	if ( empty( $strokeWeight ) ) {
		$strokeWeight = '5';
	}

	$stops = thb_trip_get_stops( thb_get_page_ID(), 'full', 'map' );
	$pictures = array();

	foreach ( $stops as $stop ) {
		$pictures[$stop['id']] = thb_trip_stop_get_pictures( $stop['id'], 'full' );
	}

	wp_localize_script( 'jquery', 'trip_stops', array(
		'type'			=> ! empty( $type ) ? $type : 'ROADMAP',
		'zoom'          => $zoom,
		'styles'        => esc_js( $styles ),
		'itinerary'     => $itinerary,
		'center'        => $center_array,
		'stops'         => $stops,
		'geodesic'      => $geodesic,
		'strokeColor'   => $strokeColor,
		'strokeOpacity' => $strokeOpacity,
		'strokeWeight'  => $strokeWeight,
		'pictures'		=> $pictures
	) );

}

/**
 * Add the admin script.
 *
 * @param array $scripts
 * @return array
 */
function thb_trip_admin_scripts( $scripts ) {
	$scripts[] = THB_THEME_CONFIG_URL . '/trips/js/admin.js';

	return $scripts;
}

thb_theme()->getAdmin()->addScript( THB_THEME_CONFIG_URL . '/trips/js/admin.js' );

/**
 * Hooks
 * -----------------------------------------------------------------------------
 */
thb_theme()->getAdmin()->addStyle( THB_THEME_CONFIG_URL . '/trips/css/admin.css' );
add_action( 'init', 'thb_trip_post_type', 9 );
add_action( 'init', 'thb_trip_post_type_metabox', 11 );
add_action( 'admin_enqueue_scripts', 'thb_trip_admin_localize_scripts' );
// add_filter( 'thb_admin_scripts', 'thb_trip_admin_scripts' );
add_action( 'wp_enqueue_scripts', 'thb_trip_localize_scripts', 15 );