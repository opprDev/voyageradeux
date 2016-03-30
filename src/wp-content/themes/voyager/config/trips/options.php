<?php

/**
 * Main page options tab.
 */
function thb_trip_options() {
	$thb_page = thb_theme()->getAdmin()->getMainPage();

	$thb_tab = new THB_Tab( __('Trips', 'thb_text_domain'), 'trips' );
		$thb_container = $thb_tab->createContainer( '', 'trips_options' );

		$thb_field = new THB_TextField( 'trips_url_slug' );
			$thb_field->setLabel( __( 'URL slug', 'thb_text_domain' ) );
			$thb_field->setHelp( sprintf( __( 'URL slug for Trips items. Defaults to "trip". Remember to <a href="%s">re-save the site\'s permalinks</a>.', 'thb_text_domain' ), admin_url( 'options-permalink.php' )) );
		$thb_container->addField($thb_field);

		$thb_field = new THB_TextField( 'trips_map_button_text' );
			$thb_field->setLabel( __( 'Map button text', 'thb_text_domain' ) );
		$thb_container->addField($thb_field);

	$thb_page->addTab($thb_tab);
}

add_action( 'init', 'thb_trip_options' );