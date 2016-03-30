<?php

/**
 * Get a trip subtitle.
 *
 * @param integer $id
 * @return string
 */
function thb_get_trip_subtitle( $id = null ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	return thb_get_post_meta( $id, 'subtitle' );
}

/**
 * Print a trip subtitle.
 *
 * @param integer $id
 */
function thb_trip_subtitle( $id = null ) {
	echo thb_get_trip_subtitle( $id );
}

/**
 * Retrieve a trip stops.
 *
 * @param integer $id
 * @param string $image_size
 * @return array
 */
function thb_trip_get_stops( $id = null, $image_size = 'full', $mode = 'page' ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	$duplicable = thb_duplicable_get( 'stop', $id );
	$stops = array();

	foreach ( $duplicable as $st ) {
		$decoded_field_value = html_entity_decode( $st['value'], ENT_QUOTES );
		parse_str( $decoded_field_value, $stop );
		$stop = stripslashes_deep( $stop );

		$latlong = $stop['latlong'];
		$latlong = str_replace( ' ', '', $latlong );
		$latlong_array = explode( ',', $latlong );

		$stop_pictures = thb_isset( $stop, 'voyager_trip_stop_slide', array() );

		$overlay_display = '';
		$overlay_color = '';
		$overlay_opacity = '';
		$background_color = '';
		$background_image = '';

		if ( isset( $stop['background'] ) ) {
			$overlay_display = $stop['background']['overlay_display'];
			$overlay_color = $stop['background']['overlay_color'];
			$overlay_opacity = $stop['background']['overlay_opacity'];
			$background_color = $stop['background']['background_color'];
			$background_image = $stop['background']['id'];
		}

		$stop_pictures_count = count( $stop_pictures );
		$mosaic_module = trim( thb_isset( $stop, 'mosaic_module', '' ) );
		$mosaic_gutter = thb_isset( $stop, 'mosaic_gutter', '' );
		$mosaic_image_size = thb_isset( $stop, 'mosaic_image_size', 'large' );
		$mosaic_module_repeat = 1;

		if ( $mosaic_module != '' ) {
			$mosaic_module_count = array_sum( str_split( $mosaic_module ) );

			if ( ! empty( $mosaic_module_count ) ) {
				if ( $mosaic_module_count < $stop_pictures_count ) {
					$mosaic_module_repeat = absint( $stop_pictures_count / $mosaic_module_count );
					$mosaic_module_repeat += $stop_pictures_count % $mosaic_module_count;
				}
			}
		}

		$show_on_map = (bool) thb_isset( $stop, 'show_on_map', 1 );
		$show_in_page = (bool) thb_isset( $stop, 'show_in_page', 1 );

		if ( $mode == 'map' && $latlong == '' ) {
			continue;
		}

		if ( $mode == 'page' && ! $show_in_page ) {
			continue;
		}

		$stops[] = array(
			'label'                 => $stop['label'],
			'description'           => $stop['description'],
			'layout'                => $stop['layout'],
			'latitude'              => isset( $latlong_array[0] ) ? $latlong_array[0] : '',
			'longitude'             => isset( $latlong_array[1] ) ? $latlong_array[1] : '',
			'latlong'               => $latlong,
			'background_appearance' => $stop['background_appearance'],
			'overlay_display'       => $overlay_display,
			'overlay_color'         => $overlay_color,
			'overlay_opacity'       => $overlay_opacity,
			'hide_posts'            => $stop['hide_posts'],
			'mosaic_module'			=> str_repeat( $mosaic_module, $mosaic_module_repeat ),
			'mosaic_gutter'			=> $mosaic_gutter,
			'mosaic_image_size'		=> $mosaic_image_size,
			'marker'                => thb_image_get_size( thb_isset( $stop, 'marker', 0 ), 'full' ),
			'fit_viewport'          => thb_isset( $stop, 'fit_viewport', false ),
			'background_color'      => $background_color,
			'background_image'      => $background_image,
			'stop_pictures'         => $stop_pictures,
			'show_gallery'          => thb_isset( $stop, 'show_gallery', false ),
			'id'                    => $st['meta']['uniqid'],
			'show_on_map'			=> $show_on_map,
			'show_in_page'			=> $show_in_page,
		);
	}

	return $stops;
}

/**
 * Retrieve a trip stops in a select format.
 *
 * @param integer $id
 * @return array
 */
function thb_trip_get_stops_for_select( $id = null ) {
	$stops = thb_trip_get_stops( $id );

	$options = array(
		'' => '--'
	);

	foreach ( $stops as $stop ) {
		$options[$stop['id']] = $stop['label'];
	}

	return $options;
}

/**
 * Get the defined trips.
 *
 * @return array
 */
function thb_get_trips() {
	$trips = array();
	$trip_posts = get_posts( array(
		'post_type' => 'trip'
	) );

	foreach ( $trip_posts as $post ) {
		$trip_stops = array();

		foreach ( thb_trip_get_stops_for_select( $post->ID ) as $value => $label ) {
			$trip_stops[] = array(
				'value' => $value,
				'label' => $label
			);
		}


		$trips[] = array(
			'id' => $post->ID,
			'title' => wptexturize( $post->post_title ),
			'stops' => $trip_stops
		);
	}

	wp_reset_query();

	return $trips;
}

/**
 * Get a trip posts.
 *
 * @param integer $id
 * @param boolean|string $stop_id
 * @return array
 */
function thb_trip_get_posts( $id = null, $stop_id = false ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	$meta_query_args = array(
		array(
			'key' => THB_META_KEY . 'trip',
			'value' => $id
		)
	);

	if ( $stop_id !== false ) {
		$meta_query_args[] = array(
			'key' => THB_META_KEY . 'trip_stop',
			'value' => $stop_id
		);
	}

	$posts = get_posts( array(
		'post_type'      => 'post',
		'meta_query'     => $meta_query_args,
		'order'          => 'asc',
		'posts_per_page' => 999
	) );

	wp_reset_query();

	return $posts;
}

/**
 * Get a post's trip.
 *
 * @param integer $id
 * @return WP_Post
 */
function thb_post_get_trip( $id = null ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	$trip_id = thb_get_post_meta( $id, 'trip' );

	if ( ! empty( $trip_id ) ) {
		return get_post( $trip_id );
	}

	return false;
}

/**
 * Get a list of pictures from the trip stop.
 *
 * @param  string $origin
 * @param  string $image_size
 * @return array
 */
function thb_trip_stop_get_pictures( $stop_id, $image_size = 'full' ) {
	$id = get_the_ID();
	$stops = thb_trip_get_stops( $id, $image_size );
	$pictures = array();
	$stop = false;

	foreach ( $stops as $st ) {
		if ( $st['id'] == $stop_id ) {
			$stop = $st;
			break;
		}
	}

	if ( $stop !== false ) {
		$posts = thb_trip_get_posts( $id, $stop['id'] );

		foreach ( $posts as $post ) {
			$featured_image = thb_get_featured_image( $image_size, $post->ID );

			if ( $featured_image != '' ) {
				$pictures[] = thb_get_featured_image( $image_size, $post->ID );
			}

			$gallery_field = thb_get_post_meta( $post->ID, 'gallery_field' );
			$attachments_ids = array();

			preg_match_all('/ids="([^\"]+)"/i', $gallery_field, $matches, PREG_OFFSET_CAPTURE);

			if( isset($matches[1]) && !empty($matches[1]) ) {
				$attachments_ids = explode(',', $matches[1][0][0]);
				array_walk($attachments_ids, 'trim');
			}

			foreach ( $attachments_ids as $atid ) {
				$pictures[] = thb_image_get_size( $atid, $image_size );
			}
		}
	}

	return array_unique( $pictures );
}

if( !function_exists('thb_trip_show_sidebar') ) {
	function thb_trip_show_sidebar( $show_sidebar ) {
		if ( is_single() ) {
			$trip = thb_post_get_trip();

			if ( $trip !== false ) {
				$show_sidebar = true;
			}
		}

		return $show_sidebar;
	}

	add_filter( 'thb_show_sidebar', 'thb_trip_show_sidebar' );
}

add_action( 'thb_sidebar_start', 'thb_trip_itinerary' );

if( !function_exists('thb_hide_stops') ) {
	/**
	 * Check if the hide stops option is enabled
	 *
	 * @return boolean
	 */
	function thb_hide_stops( $id = null ) {
		if ( ! $id ) {
			global $post;
			$id = thb_get_page_ID();
		}
		$value = thb_get_post_meta( $id, 'hide_stops' );

		return $value == '1';
	}
}

if( !function_exists('thb_show_map_in_page') ) {
	/**
	 * Check if the show map in page option is enabled
	 *
	 * @return boolean
	 */
	function thb_show_map_in_page( $id = null ) {
		if ( ! $id ) {
			global $post;
			$id = thb_get_page_ID();
		}
		$value = thb_get_post_meta( $id, 'show_map_in_page' );

		return $value == '1';
	}
}