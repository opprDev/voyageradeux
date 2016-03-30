<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

if( ! function_exists( 'thb_page_password_protected' ) ) {
	/**
	 * Handle password-protected pages and posts.
	 */
	function thb_page_password_protected() {
		if ( post_password_required() ) {
			get_template_part('partials/partial-pass-protected');
			get_footer();
			die();
		}
	}

	add_action( 'thb_page_before', 'thb_page_password_protected' );
	add_action( 'thb_post_before', 'thb_page_password_protected' );
}

if( ! function_exists( 'thb_get_social_networks' ) ) {
	/**
	 * Get a list of the defined social networks available for the theme.
	 * Filters empty social networks.
	 *
	 * @return array
	 */
	function thb_get_social_networks() {
		$social_networks = thb_get_option('social_networks');

		if ( ! empty( $social_networks ) ) {
			$social_networks_array = array();

			foreach ( explode( ',', $social_networks ) as $social_network ) {
				if ( thb_get_social_network_url( $social_network ) != '' ) {
					$social_networks_array[] = $social_network;
				}
			}

			return $social_networks_array;
		}

		return array();
	}
}

if( ! function_exists( 'thb_get_social_network_url' ) ) {
	/**
	 * Get the URL of a specific social network service.
	 *
	 * @param string $social_network The social network key.
	 * @return string
	 */
	function thb_get_social_network_url( $social_network ) {
		return thb_get_option( $social_network );
	}
}

if( !function_exists('thb_is_enable_social_share') ) {
	/**
	 * Check if the social share option is checked
	 * @return boolean
	 */
	function thb_is_enable_social_share() {
		if ( thb_get_option( 'enable_social_share' ) == 1 ) {
			return true;
		}
		return false;
	}
}

if( ! function_exists( 'thb_get_theme_social_options' ) ) {
	/**
	 * Get the social options for the theme.
	 *
	 * @return array
	 */
	function thb_get_theme_social_options() {
		$thb_page = thb_theme()->getAdmin()->getMainPage();
		$thb_container = $thb_page->getTab('social')->getContainer('social_options');
		$options = array();

		foreach( $thb_container->getFields() as $field ) {
			$options[$field->getName()] = $field->getLabel();
		}

		return $options;
	}
}

if( ! function_exists( 'thb_is_blog_likes_active' ) ) {
	/**
	 * Check if likes have been activated for Blog posts.
	 *
	 * @return boolean
	 */
	function thb_is_blog_likes_active() {
		return (int) thb_get_option( 'thb_blog_likes_active' ) == 1;
	}
}

if( ! function_exists( 'thb_is_portfolio_likes_active' ) ) {
	/**
	 * Check if likes have been activated for Portfolio items.
	 *
	 * @return boolean
	 */
	function thb_is_portfolio_likes_active() {
		return (int) thb_get_option( 'thb_portfolio_likes_active' ) == 1;
	}
}

if( !function_exists('thb_get_page_layout') ) {
	/**
	 * Get the page layout option value
	 *
	 * @return string
	 */
	function thb_get_page_layout() {
		$thb_get_page_layout = thb_get_post_meta( thb_get_page_ID(), 'voyager_page_layout' );

		if( empty( $thb_get_page_layout ) ) {
			$thb_get_page_layout = 'page-layout-a';
		}

		if ( ( thb_is_header_layout_b() || is_active_sidebar( 'hamburger-sidebar' ) ) && $thb_get_page_layout == 'page-layout-g' ) {
			$thb_get_page_layout = 'page-layout-a';
		}

		if ( post_password_required() ) {
			$thb_get_page_layout = 'page-layout-a';
		}

		$thb_get_page_layout = apply_filters( 'thb_get_page_layout', $thb_get_page_layout );

		return $thb_get_page_layout;
	}
}

if( !function_exists('thb_is_page_layout_a') ) {
	/**
	 * Check if the page header layout is "A"
	 * standard page header above the featured image
	 *
	 * @return boolean
	 */
	function thb_is_page_layout_a() {
		if ( thb_get_page_layout() === 'page-layout-a' ) {
			return true;
		}

		return false;
	}
}

if( !function_exists('thb_is_page_layout_b') ) {
	/**
	 * Check if the page header layout is "B"
	 * standard page header below the featured image
	 *
	 * @return boolean
	 */
	function thb_is_page_layout_b() {
		if ( thb_get_page_layout() === 'page-layout-b' ) {
			return true;
		}

		return false;
	}
}

if( !function_exists('thb_is_page_layout_c') ) {
	/**
	 * Check if the page header layout is "C"
	 * 576px height image with page header below
	 *
	 * @return boolean
	 */
	function thb_is_page_layout_c() {
		if ( thb_get_page_layout() === 'page-layout-c' ) {
			return true;
		}

		return false;
	}
}

if( !function_exists('thb_is_page_layout_d') ) {
	/**
	 * Check if the page header layout is "D"
	 * 576px height image with page header overlapped
	 *
	 * @return boolean
	 */
	function thb_is_page_layout_d() {
		if ( thb_get_page_layout() === 'page-layout-d' ) {
			return true;
		}

		return false;
	}
}

if( !function_exists('thb_is_page_layout_e') ) {
	/**
	 * Check if the page header layout is "E"
	 * full viewport height image with page header below
	 *
	 * @return boolean
	 */
	function thb_is_page_layout_e() {
		if ( thb_get_page_layout() === 'page-layout-e' ) {
			return true;
		}

		return false;
	}
}

if( !function_exists('thb_is_page_layout_f') ) {
	/**
	 * Check if the page header layout is "F"
	 * full viewport height image with page header overlapped
	 *
	 * @return boolean
	 */
	function thb_is_page_layout_f() {
		if ( thb_get_page_layout() === 'page-layout-f' ) {
			return true;
		}

		return false;
	}
}


if( !function_exists('thb_is_page_layout_g') ) {
	/**
	 * Check if the page header layout is "G"
	 * half viewport width and full viewport height image with page header overlapped
	 *
	 * @return boolean
	 */
	function thb_is_page_layout_g() {
		if ( thb_get_page_layout() === 'page-layout-g' ) {
			return true;
		}

		return false;
	}
}

if( !function_exists('thb_get_header_layout') ) {
	/**
	 * Get the main theme header layout option value
	 *
	 * @return string
	 */
	function thb_get_header_layout() {
		$thb_header_layout = thb_get_option('header_layout');
		$thb_header_layout = apply_filters( 'thb_get_header_layout', $thb_header_layout );

		if ( empty( $thb_header_layout ) ) {
			return 'header-layout-a';
		}

		return $thb_header_layout;
	}
}

if( !function_exists('thb_is_header_layout_a') ) {
	/**
	 * Check if the header layout is "A"
	 * standard inline page navigation
	 *
	 * @return boolean
	 */
	function thb_is_header_layout_a() {
		if ( thb_get_header_layout() === 'header-layout-a' ) {
			return true;
		}

		return false;
	}
}

if( !function_exists('thb_is_header_layout_b') ) {
	/**
	 * Check if the header layout is "B"
	 * hamburger navigation
	 *
	 * @return boolean
	 */
	function thb_is_header_layout_b() {
		if ( thb_get_header_layout() === 'header-layout-b' ) {
			return true;
		}

		return false;
	}
}

if( !function_exists('thb_is_thb_sticky_header') ) {
	/**
	 * Check if the sticky header option is active
	 * @return boolean
	 */
	function thb_is_thb_sticky_header() {
		$thb_sticky_header = thb_get_option( 'thb_sticky_header' ) == 1;
		$thb_sticky_header = apply_filters( 'thb_sticky_header', $thb_sticky_header );

		return (int) $thb_sticky_header;
	}
}

if( !function_exists('thb_is_page_layout_extended') ) {
	/**
	 * Check if the page layout is extended.
	 *
	 * @return boolean
	 */
	function thb_is_page_layout_extended() {
		if (
			thb_is_page_layout_c() ||
			thb_is_page_layout_d() ||
			thb_is_page_layout_e() ||
			thb_is_page_layout_f() ||
			thb_is_page_layout_g()
		) {
			return true;
		}

		return false;
	}
}

if( !function_exists('thb_is_page_layout_extended_with_title') ) {
	/**
	 * Check if the page layout is extended with the page title displayed on top of it.
	 *
	 * @return boolean
	 */
	function thb_is_page_layout_extended_with_title() {
		if (
			thb_is_page_layout_d() ||
			thb_is_page_layout_f() ||
			thb_is_page_layout_g()
		) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'thb_voyager_pageheader_parallax' ) ) {
	/**
	 * Check if the page header has parallax.
	 *
	 * @return boolean
	 */
	function thb_voyager_pageheader_parallax() {
		return thb_get_post_meta( thb_get_page_ID(), 'voyager_page_header_parallax' ) == '1';
	}
}

if ( ! function_exists( 'thb_voyager_get_slide_navigation_skin' ) ) {
	/**
	 * Get the CSS skin class to be applied to the slide navigation element.
	 *
	 * @return string
	 */
	function thb_voyager_get_slide_navigation_skin() {
		$skin = '';

		$slide_navigation_background_color = get_theme_mod( 'slide_navigation_bg', '#222' );
		$skin = 'thb-skin-' . thb_color_get_skin_from_comparison( $slide_navigation_background_color );

		return esc_attr( $skin );
	}
}

if ( ! function_exists( 'thb_voyager_get_pageheader_skin' ) ) {
	/**
	 * Get the CSS skin class to be applied to the page-header-wrapper element, and the header.
	 *
	 * @return string
	 */
	function thb_voyager_get_pageheader_skin() {
		$skin = '';
		$pagecontent_color = get_theme_mod('body_bg', '#ffffff');

		if ( thb_is_page_layout_extended_with_title() ) {
			$overlay_color = thb_get_post_meta( thb_get_page_ID(), 'header_background_overlay_color' );
			$header_background_color = thb_get_post_meta( thb_get_page_ID(), 'header_background_background_color' );
			$skin = 'thb-skin-' . thb_color_get_skin_from_comparison( $overlay_color, $header_background_color, $pagecontent_color );
		} else {
			$skin = thb_voyager_pagecontent_skin();
		}

		$skin = apply_filters( 'thb_voyager_get_pageheader_skin', $skin );

		return esc_attr( $skin );
	}
}

if ( ! function_exists( 'thb_voyager_get_header_skin' ) ) {
	/**
	 * Get the CSS skin class to be applies to the page-header-wrapper element, and the header.
	 *
	 * @return string
	 */
	function thb_voyager_get_header_skin() {
		$skin = '';
		$pagecontent_color = get_theme_mod('body_bg', '#ffffff');

		if ( thb_is_page_layout_extended() ) {
			$overlay_color = thb_get_post_meta( thb_get_page_ID(), 'header_background_overlay_color' );
			$header_background_color = thb_get_post_meta( thb_get_page_ID(), 'header_background_background_color' );
			$skin = 'thb-skin-' . thb_color_get_skin_from_comparison( $overlay_color, $header_background_color, $pagecontent_color );
		} else {
			$skin = thb_voyager_pagecontent_skin();
		}

		return esc_attr( $skin );
	}
}

if( !function_exists('thb_voyager_footer_skin') ) {
	/**
	 * Get the CSS skin class to be applied to the page footer
	 *
	 * @return string
	 */
	function thb_voyager_footer_skin() {
		$bg_color = get_theme_mod('footer_bg', '#222222');

		if ( empty( $bg_color ) ) {
			$skin = 'thb-skin-dark';
		}
		else {
			$skin = 'thb-skin-' . thb_color_get_opposite_skin( $bg_color );
		}

		return esc_attr( $skin );
	}
}

if( !function_exists('thb_voyager_footer_sidebar_skin') ) {
	/**
	 * Get the CSS skin class to be applied to the page footer sidebar
	 *
	 * @return string
	 */
	function thb_voyager_footer_sidebar_skin() {
		$bg_color = get_theme_mod('footer_sidebar_bg', '#f9f9f9');

		if ( empty( $bg_color ) ) {
			$skin = 'thb-skin-dark';
		}
		else {
			$skin = 'thb-skin-' . thb_color_get_opposite_skin( $bg_color );
		}

		return esc_attr( $skin );
	}
}

if( !function_exists('thb_voyager_pagecontent_skin') ) {
	/**
	 * Get the CSS skin class to be applied to the page content
	 *
	 * @return string
	 */
	function thb_voyager_pagecontent_skin() {
		$bg_color = get_theme_mod('body_bg', '#ffffff');

		if ( empty( $bg_color ) ) {
			$skin = 'thb-skin-dark';
		}
		else {
			$skin = 'thb-skin-' . thb_color_get_opposite_skin( $bg_color );
		}

		return esc_attr( $skin );
	}
}

if( !function_exists('thb_get_subtitle_position') ) {
	function thb_get_subtitle_position( $id = null ) {
		if ( ! $id ) {
			$id = thb_get_page_ID();
		}

		$subtitle_position = thb_get_post_meta( $id, 'voyager_subtitle_position' );

		if ( empty( $subtitle_position ) ) {
			return 'subtitle-bottom';
		}

		return $subtitle_position;
	}
}

if( !function_exists('thb_is_subtitle_position_top') ) {
	/**
	 * Check if the subtitle position is top
	 * @return boolean
	 */
	function thb_is_subtitle_position_top() {
		if ( thb_get_subtitle_position() == 'subtitle-top' ) {
			return true;
		}
		return false;
	}
}

if( !function_exists('thb_is_subtitle_position_bottom') ) {
	/**
	 * Check if the subtitle position is bottom
	 * @return boolean
	 */
	function thb_is_subtitle_position_bottom() {
		if ( thb_get_subtitle_position() == 'subtitle-bottom' ) {
			return true;
		}
		return false;
	}
}

if( !function_exists('thb_get_project_short_description') ) {
	/**
	 * Get the project short description
	 *
	 * @return string
	 */
	function thb_get_project_short_description( $id = null ) {
		if ( ! $id ) {
			global $post;
			$id = thb_get_page_ID();
		}

		return thb_get_post_meta( $id, 'project_short_description' );
	}
}

if( !function_exists('thb_get_project_url') ) {
	/**
	 * Get the project URL
	 *
	 * @return string
	 */
	function thb_get_project_url() {
		return thb_get_post_meta( thb_get_page_ID(), 'project_url' );
	}
}

if( !function_exists('thb_get_portfolio_layout') ) {
	/**
	 * Get the 'one_portfolio_layout' portfolio meta value
	 *
	 * @return string
	 */
	function thb_get_portfolio_layout( $layout = false ) {
		if ( $layout === false ) {
			$thb_get_portfolio_layout = thb_get_post_meta( thb_get_page_ID(), 'voyager_portfolio_layout' );
		}
		else {
			$thb_get_portfolio_layout = $layout;
		}

		if( empty( $thb_get_portfolio_layout ) ) {
			return 'thb-portfolio-grid-a';
		}

		return $thb_get_portfolio_layout;
	}
}

if( !function_exists('thb_is_portfolio_grid_a') ) {
	/**
	 * Check if the portfolio grid layout is A
	 * @return boolean
	 */
	function thb_is_portfolio_grid_a() {
		if ( thb_get_portfolio_layout() == 'thb-portfolio-grid-a' ) {
			return true;
		}
		return false;
	}
}

if( !function_exists('thb_is_portfolio_grid_b') ) {
	/**
	 * Check if the portfolio grid layout is B
	 * @return boolean
	 */
	function thb_is_portfolio_grid_b() {
		if ( thb_get_portfolio_layout() == 'thb-portfolio-grid-b' ) {
			return true;
		}
		return false;
	}
}

if( !function_exists('thb_is_portfolio_grid_c') ) {
	/**
	 * Check if the portfolio grid layout is C
	 * @return boolean
	 */
	function thb_is_portfolio_grid_c() {
		if ( thb_get_portfolio_layout() == 'thb-portfolio-grid-c' ) {
			return true;
		}
		return false;
	}
}

if( !function_exists('thb_get_portfolio_filter_alignment') ) {
	/**
	 * Get the 'one_portfolio_filter_alignment' portfolio meta value
	 *
	 * @return string
	 */
	function thb_get_portfolio_filter_alignment() {
		$thb_get_portfolio_filter_alignment = thb_get_post_meta( thb_get_page_ID(), 'voyager_portfolio_filter_alignment' );

		if( empty( $thb_get_portfolio_filter_alignment ) ) {
			return 'filter-alignment-left';
		}

		return $thb_get_portfolio_filter_alignment;
	}
}

if( !function_exists('thb_get_disable_work_image_link') ) {
	/**
	 * Return the disable work image link checkbox value
	 */
	function thb_get_disable_work_image_link( $id = null ) {
		if ( ! $id ) {
			$id = thb_get_page_ID();
		}

		return thb_get_post_meta( $id, 'disable_work_image_link' );
	}
}

if( !function_exists('thb_get_hide_featured_image') ) {
	/**
	 * Return the hide featured image checkbox value
	 */
	function thb_get_hide_featured_image( $id = null ) {
		if ( ! $id ) {
			$id = thb_get_page_ID();
		}

		return thb_get_post_meta( $id, 'hide_featured_image' );
	}
}

if ( ! function_exists( 'thb_has_side_menu' ) ) {
	/**
	 * Check if the side navigation is enabled.
	 *
	 * @return boolean
	 */
	function thb_has_side_menu() {
		$full_menu = wp_nav_menu( array ( 'echo' => false, 'fallback_cb' => '__return_false' ) );
		$primary_menu = wp_nav_menu( array ( 'theme_location' => 'primary', 'echo' => false, 'fallback_cb' => '__return_false' ) );
		$mobile_menu = wp_nav_menu( array ( 'theme_location' => 'mobile', 'echo' => false, 'fallback_cb' => '__return_false' ) );

		return ( $primary_menu !== false || $mobile_menu !== false || $full_menu !== false || is_active_sidebar( 'hamburger-sidebar' ) );
	}
}

if( ! function_exists('thb_logo') ) {
	/**
	 * Display a graphic logo or its textual counterpart.
	 */
	function thb_logo() {
		$logo             = apply_filters( 'thb_logo', thb_get_option( 'main_logo' ) );
		$logo_white       = apply_filters( 'thb_logo_white', thb_get_option( 'white_main_logo' ) );
		$logo_2x          = apply_filters( 'thb_logo_2x', thb_get_option( 'main_logo_retina' ) );
		$logo_2x_white    = apply_filters( 'thb_logo_2x_white', thb_get_option( 'white_main_logo_retina' ) );
		$logo_description = apply_filters( 'thb_logo_description', get_bloginfo( 'description' ) );

		$args = array(
			'logo'          => thb_image_get_size( $logo ),
			'logo_white'    => thb_image_get_size( $logo_white ),
			'logo_2x'       => thb_image_get_size( $logo_2x ),
			'logo_2x_white' => thb_image_get_size( $logo_2x_white ),
			'description'   => $logo_description,
		);

		if ( ! empty( $args['logo'] ) && ! empty( $args['logo_2x'] ) ) {
			$args['logo_metadata'] = wp_get_attachment_metadata( $logo );
		}

		thb_get_subtemplate( 'backpack/general', dirname(__FILE__), 'logo', $args );
	}
}