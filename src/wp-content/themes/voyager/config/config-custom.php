<?php if( !defined('THB_FRAMEWORK_NAME') ) exit('No direct script access allowed.');

/**
 * Theme customizations.
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

$thb_theme = thb_theme();

if( ! function_exists('thb_comment_form_fields') ) {
	/**
	 * Customizations for the form
	 */
	function thb_comment_form_fields( $fields ) {
		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$args = array(
			'format' => 'xhtml'
		);
		$html5 = 'html5' === $args['format'];
		$args = wp_parse_args( $args );

		if ( ! isset( $args['format'] ) )
			$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';

		$fields['author'] = '<p class="comment-form-author">' . '<label for="author">' . __( 'Name','thb_text_domain' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' . '<input id="author" name="author" type="text" placeholder="' . __('Your name *', 'thb_text_domain') . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
		$fields['email'] = '<p class="comment-form-email"><label for="email">' . __( 'Email','thb_text_domain' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
						'<input id="email" name="email" placeholder="' . __('Your email *','thb_text_domain') . '" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
		$fields['url'] = '<p class="comment-form-url"><label for="url">' . __( 'Website','thb_text_domain' ) . '</label> ' .
						'<input id="url" name="url" placeholder="' . __('Your website url', 'thb_text_domain') . '" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

		return $fields;
	}

	add_filter('comment_form_default_fields','thb_comment_form_fields');
}

if( !function_exists('thb_password_form') ) {
	/**
	 * THB custom password protection form
	 */
	function thb_password_form() {
		 global $post;
	    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	    $o = '<p class="thb-password-protected-message">' . __( "This content is password protected", 'thb_text_domain') . '<span>' . __("to view it please enter your password below", 'thb_text_domain') . '</span></p>
	    <form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
			<label class="hidden" for="' . $label . '">' . __( "Password:",'thb_text_domain' ) . ' </label>
			<input name="post_password" placeholder="Password" id="' . $label . '" type="password" size="20" maxlength="20" />
			<input id="submit" type="submit" name="Submit" value="' . esc_attr__( "Submit" ) . '" />
		</form>
	    ';
	    return $o;
	}
	add_filter( 'the_password_form', 'thb_password_form' );
}

if( !function_exists('thb_title_format') ) {
	/**
	 * Change the title for the protected content
	 */
	function thb_title_format($content) {
		return '%s';
	}

	add_filter('private_title_format', 'thb_title_format');
	add_filter('protected_title_format', 'thb_title_format');
}

if ( ! function_exists( 'thb_generate_hamburger_sidebar' ) ) {
	function thb_generate_hamburger_sidebar() {
		thb_theme()->addSidebar( __('Hamburger sidebar', 'thb_text_domain'), 'hamburger-sidebar', __( 'Sidebar loaded in the website side drawer.', 'thb_text_domain' ) );
	}

	add_action( 'after_setup_theme', 'thb_generate_hamburger_sidebar', 11 );
}

if ( ! function_exists( 'thb_hamburger_nav_icon' ) ) {
	function thb_hamburger_nav_icon() {
		echo '<a id="thb-trigger-open" href="#">';
			echo '<span class="line line-1"></span>';
				echo '<span class="line line-2"></span>';
				echo '<span class="line line-3"></span>';
		echo '</a>';
	}
}

if( !function_exists('thb_main_navigation') ) {
	/**
	 * Add the main navigation inside the #page-content
	 */
	function thb_main_navigation() {
		if ( ! thb_is_page_template( 'template-blank.php' ) ) {
			thb_get_template_part( 'partials/partial-inline-navigation' );
		}
	}

	add_action( 'thb_page_content_start', 'thb_main_navigation' );
	add_action( 'thb_single_content_start', 'thb_main_navigation' );
}

if( !function_exists('thb_page_header') ) {
	/**
	 * Add the page header to the #header
	 */
	function thb_page_header() {
		$params = array();

		if ( is_404() ) {
			$params['thb_title'] = __('404', 'thb_text_domain');
			$params['thb_subtitle'] = __('Page not found', 'thb_text_domain');
		}
		elseif( is_category() ) {
			$params['thb_title'] = single_cat_title( '', false );
			$params['thb_subtitle'] = __("Articles from this Category", 'thb_text_domain');
		}
		elseif( is_tag() ) {
			$params['thb_title'] = single_cat_title( '', false );
			$params['thb_subtitle'] = __("Articles from this Tag", 'thb_text_domain');
		}
		elseif( is_tax() ) {
			$params['thb_title'] = single_cat_title( '', false );
			$params['thb_subtitle'] = __("Articles from this Taxonomy", 'thb_text_domain');

			if ( is_tax( 'portfolio_categories' ) ) {
				$params['thb_subtitle'] = __("Projects from this Taxonomy", 'thb_text_domain');
			}
		}
		elseif( is_search() ) {
			$params['thb_subtitle'] = __( 'Search Results', 'thb_text_domain' );
			$params['thb_title'] = ' &ldquo;' . get_search_query() . '&rdquo;';
		}
		elseif( is_author() ) {
			if( have_posts() ) {
				the_post();
				$params['thb_title'] = get_the_author();
				$params['thb_subtitle'] = __("Author archive", 'thb_text_domain');
				rewind_posts();
			}
		}
		elseif ( is_day() ) {
			$params['thb_title'] = get_the_date();
			$params['thb_subtitle'] = __("Archives", 'thb_text_domain');
		}
		elseif ( is_month() ) {
			$params['thb_title'] = get_the_date( 'F Y' );
			$params['thb_subtitle'] = __("Archives", 'thb_text_domain');
		}
		elseif ( is_year() ) {
			$params['thb_title'] = get_the_date( 'Y' );
			$params['thb_subtitle'] = __("Archives", 'thb_text_domain');
		}
		elseif ( is_attachment() ) {
			add_filter( 'thb_pageheader_disable', '__return_true' );
		}
		elseif( post_password_required() ) {
			add_filter( 'thb_pageheader_disable', '__return_true' );
		}

		$params = apply_filters( 'thb_page_header', $params );

		thb_get_template_part( 'partials/partial-pageheader', $params );
	}

	add_action( 'thb_header_end', 'thb_page_header' );
}

if( !function_exists('thb_single_next_custom_pagination') ) {
	/**
	 * Display a custom next pagination link in the single post.
	 */
	function thb_single_next_custom_pagination() {
		$post = get_next_post();

		if ( $post == '' ) {
			return;
		}

		echo "<div class='thb-single-next-nav-wrapper'>";
			echo "<a href='" . get_permalink( $post->ID ) . "'>";
				echo "<span>" . __( 'Next post', 'thb_text_domain' ) . "</span>";
				echo "<p class='thb-single-nav-title'>" . $post->post_title . "</p>";
				echo "<p class='thb-single-nav-the-date'>";
					echo date( get_option('date_format'), strtotime( $post->post_date ) );
				echo "</p>";
			echo "</a>";
		echo "</div>";
	}
}

if( !function_exists('thb_single_prev_custom_pagination') ) {
	/**
	 * Display a custom previous pagination link in the single post.
	 */
	function thb_single_prev_custom_pagination() {
		$post = get_previous_post();

		if ( $post == '' ) {
			return;
		}

		echo "<div class='thb-single-previous-nav-wrapper'>";
			echo "<a href='" . get_permalink( $post->ID ) . "'>";
				echo "<span>" . __( 'Previous post', 'thb_text_domain' ) . "</span>";
				echo "<p class='thb-single-nav-title'>" . $post->post_title . "</p>";
				echo "<p class='thb-single-nav-the-date'>";
					echo date( get_option('date_format'), strtotime( $post->post_date ) );
				echo "</p>";
			echo "</a>";
		echo "</div>";
	}
}

if( !function_exists('thb_builder_load') ) {
	/**
	 * Attach the builder functionality to every page_end hook
	 */
	function thb_builder_load() {
		if ( thb_is_builder_position_top() ) {
			add_action( 'thb_page_content_start', 'thb_builder' );
			add_action( 'thb_single_content_start', 'thb_builder' );
		}
		else {
			add_action( 'thb_page_end', 'thb_builder' );
			add_action( 'thb_post_end', 'thb_builder' );
		}
		// thb_builder();
	}

	add_action( 'thb_before_doctype', 'thb_builder_load' );
}

if( ! function_exists('thb_theme_layout_options') ) {
	/**
	 * Page extra layout features
	 */
	function thb_theme_layout_options() {
		foreach( thb_theme()->getPublicPostTypes() as $post_type ) {
			if ( ! $thb_metabox = $post_type->getMetabox('layout') ) {
				return;
			}

			$page_header_options = array(
				'page-layout-a' => get_template_directory_uri() . '/css/i/options/page-layout-a.png',
				'page-layout-b' => get_template_directory_uri() . '/css/i/options/page-layout-b.png',
				'page-layout-c' => get_template_directory_uri() . '/css/i/options/page-layout-c.png',
				'page-layout-d' => get_template_directory_uri() . '/css/i/options/page-layout-d.png',
				'page-layout-e' => get_template_directory_uri() . '/css/i/options/page-layout-e.png',
				'page-layout-f' => get_template_directory_uri() . '/css/i/options/page-layout-f.png'
			);

			if( thb_is_header_layout_b() && is_active_sidebar( 'hamburger-sidebar' ) ) {
				$page_header_options['page-layout-g'] = get_template_directory_uri() . '/css/i/options/page-layout-g.png';
			}

			$all_templates = thb_get_theme_templates();

			if( thb_is_admin_template( $all_templates ) ) {

				$thb_container = $thb_metabox->getContainer( 'layout_container' );

				$thb_field = new THB_GraphicRadioField( 'voyager_page_layout' );
					$thb_field->setLabel( __( 'Page layout', 'thb_text_domain' ) );
					$thb_field->setOptions( $page_header_options );
				$thb_container->addField($thb_field);

				$thb_field = new THB_SelectField( 'voyager_page_header_alignment' );
					$thb_field->setLabel( __( 'Page header alignment', 'thb_text_domain' ) );
					$thb_field->setOptions(array(
						'pageheader-alignment-left'   => __('Left', 'thb_text_domain'),
						'pageheader-alignment-center' => __('Center', 'thb_text_domain'),
						'pageheader-alignment-right'  => __('Right', 'thb_text_domain')
					));
				$thb_container->addField($thb_field);

				$thb_field = new THB_SelectField( 'voyager_subtitle_position' );
					$thb_field->setLabel( __( 'Page subtitle position', 'thb_text_domain' ) );
					$thb_field->setOptions(array(
						'subtitle-bottom' => __('Bottom', 'thb_text_domain'),
						'subtitle-top' => __('Top', 'thb_text_domain')
					));
				$thb_container->addField($thb_field);

				$thb_field = new THB_CheckboxField( 'voyager_page_header_parallax' );
					$thb_field->setLabel( __( 'Parallax on page header', 'thb_text_domain' ) );
					$thb_field->setHelp( __( 'Parallax will work on extended horizontal headers only.', 'thb_text_domain' ) );
				$thb_container->addField($thb_field);

				$thb_field = new THB_BackgroundField( 'header_background' );
				$thb_field->setLabel( __( 'Header background', 'thb_text_domain' ) );
				$thb_field->setHelp( __( 'In extended page header configurations, the color of the overlay (even if not enabled) or background determines the skin used for texts (e.g. a dark color automatically generates light text).<br><br>If present, the slideshows have precedence over this field.', 'thb_text_domain' ) );
				$thb_container->addField($thb_field);

			}

		}
	}

	add_action('wp_loaded', 'thb_theme_layout_options');
}

if( ! function_exists( 'thb_theme_portfolio_options' ) ) {
	/**
	 * Theme portfolio options.
	 */
	function thb_theme_portfolio_options() {
		if( thb_is_admin_template( 'template-portfolio.php' ) ) {

			$thb_metabox = thb_theme()->getPostType( 'page' )->getMetabox( 'layout' );
			$thb_tab = $thb_metabox->getTab( 'portfolio_options' );
			$thb_container = $thb_tab->getContainer( 'portfolio_loop_container' );

			$thb_field = new THB_GraphicRadioField( 'voyager_portfolio_layout' );
			$thb_field->setLabel( __('Portfolio layout', 'thb_text_domain') );
			$thb_field->setOptions(array(
				'thb-portfolio-grid-a' => get_template_directory_uri() . '/css/i/options/portfolio-grid-a.png',
				'thb-portfolio-grid-b' => get_template_directory_uri() . '/css/i/options/portfolio-grid-b.png',
				'thb-portfolio-grid-c' => get_template_directory_uri() . '/css/i/options/portfolio-grid-c.png'
			));
			$thb_container->addField($thb_field);

			$thb_field = new THB_SelectField( 'voyager_portfolio_filter_alignment' );
				$thb_field->setLabel( __( 'Filter alignment', 'thb_text_domain' ) );
				$thb_field->setOptions(array(
					'filter-alignment-left'   => __('Left', 'thb_text_domain'),
					'filter-alignment-center' => __('Center', 'thb_text_domain'),
					'filter-alignment-right'  => __('Right', 'thb_text_domain')
				));
			$thb_container->addField($thb_field);
		}

		if( thb_is_admin_template( 'single-works.php' ) ) {

			$thb_metabox = thb_theme()->getPostType( 'works' )->getMetabox( 'layout' );
			$thb_tab = $thb_metabox->getTab( 'extra' );
			$thb_container = $thb_tab->getContainer( 'data_details' );

				$thb_field = new THB_TextField( 'project_short_description' );
					$thb_field->setLabel( __('Project short description', 'thb_text_domain') );
					$thb_field->setHelp( __('You can place here a short description or the tagline for your project.', 'thb_text_domain') );
				$thb_container->addField($thb_field);

				$thb_field = new THB_CheckboxField( 'hide_featured_image' );
					$thb_field->setLabel( __( 'Hide the featured image', 'thb_text_domain') );
					$thb_field->setHelp( __('Tick if you want to hide the featured image on single work page.', 'thb_text_domain') );
				$thb_container->addField($thb_field);

			$thb_container = $thb_tab->createContainer( __( 'Pictures appearance', 'thb_text_domain' ), 'pictures_appearance', 1 );

				$thb_field = new THB_SelectField( 'voyager_single_work_display' );
					$thb_field->setLabel( __( 'Media display', 'thb_text_domain') );
					$thb_field->setHelp( __( 'Mosaic display will not work if when having both pictures and videos.', 'thb_text_domain' ) );
					$thb_field->setOptions( array(
						''          => __( 'Regular', 'thb_text_domain' ),
						'slideshow' => __( 'Slideshow', 'thb_text_domain' ),
						'mosaic'    => __( 'Mosaic', 'thb_text_domain' ),
					) );
				$thb_container->addField($thb_field);

				$thb_field = new THB_TextField( 'voyager_single_work_mosaic_module' );
				$thb_field->setLabel( __( 'Mosaic module', 'thb_text_domain' ) );
				$thb_field->setHelp( __( 'E.g. 231 will produce three rows, the 1st with two images, the 2nd with three, etc.', 'thb_text_domain' ) );
				$thb_container->addField( $thb_field );

				$thb_field = new THB_NumberField( 'voyager_single_work_mosaic_gutter' );
				$thb_field->setLabel( __( 'Mosaic gutter', 'thb_text_domain' ) );
				$thb_field->setHelp( __( 'Space between images, in pixels.', 'thb_text_domain' ) );
				$thb_field->setMin( 0 );
				$thb_container->addField( $thb_field );

				$thb_field = new THB_SelectField( 'voyager_single_work_mosaic_image_size' );
				$thb_field->setLabel( __( 'Image size', 'thb_text_domain' ) );
				$thb_field->setOptions( array(
					'large'     => __( 'Large', 'thb_text_domain' ),
					'medium'    => __( 'Medium', 'thb_text_domain' ),
					'thumbnail' => __( 'Small', 'thb_text_domain' ),
					'full'      => __( 'Full', 'thb_text_domain' ),
				) );
				$thb_container->addField( $thb_field );

				$thb_field = new THB_CheckboxField( 'disable_work_image_link' );
					$thb_field->setLabel( __( 'Disable images lightbox', 'thb_text_domain') );
					$thb_field->setHelp( __('Tick if you want to disable creation of a link that will open the image in a lightbox for this work.', 'thb_text_domain') );
				$thb_container->addField($thb_field);

		}
	}

	if ( function_exists( 'thb_portfolio_loop' ) ) {
		add_action( 'wp_loaded', 'thb_theme_portfolio_options' );
	}
}

if( !function_exists('thb_theme_body_classes') ) {
	/**
	 * THB custom body classes
	 */
	function thb_theme_body_classes( $classes ) {
		$thb_id = thb_get_page_ID();

		$classes[] = thb_get_header_layout();
		$classes[] = thb_get_page_header_alignment();
		$classes[] = thb_get_page_layout();
		$classes[] = thb_get_subtitle_position();

		// if ( thb_is_thb_sticky_header() ) {
		// 	$classes[] = 'thb-sticky-header';
		// }

		if ( thb_voyager_pageheader_parallax() ) {
			$classes[] = 'thb-pageheader-parallax';
		}

		if ( thb_has_side_menu() ) {
			$classes[] = 'thb-has-side-menu';
		}

		if ( is_active_sidebar( 'hamburger-sidebar' ) ) {
			$classes[] = 'thb-hamburger-sidebar';
		}

		if ( empty( $thb_id ) ) {
			return $classes;
		}

		return $classes;
	}

	add_filter('body_class', 'thb_theme_body_classes');
}

if( !function_exists('thb_get_page_header_alignment') ) {
	/**
	 * Get the page header alignment option value
	 *
	 * @return string
	 */
	function thb_get_page_header_alignment() {
		$thb_get_page_header_alignment = thb_get_post_meta( thb_get_page_ID(), 'voyager_page_header_alignment' );

		if( empty( $thb_get_page_header_alignment ) ) {
			if ( is_archive() || is_search() ) {
				return 'pageheader-alignment-center';
			} else {
				return 'pageheader-alignment-left';
			}
		}

		return apply_filters( 'thb_get_page_header_alignment', $thb_get_page_header_alignment );
	}
}

if( !function_exists( 'thb_portfolio_index' ) ) {
	/**
	 * Print the back to portfolio link
	 *
	 * @return html
	 */
	function thb_portfolio_index() {
		$thb_portfolio_index = thb_portfolio_get_index( thb_get_page_ID() );
	?>
		<?php if ( !empty( $thb_portfolio_index ) ) : ?>
			<a class="back-to-portfolio" href="<?php echo get_permalink( $thb_portfolio_index ); ?>">
				<span><?php _e('Back to Portfolio', 'thb_text_domain' ); ?></span>
			</a>
		<?php endif; ?>
	<?php }
}

if( ! function_exists( 'thb_portfolio_likes_container' ) ) {
	/**
	 * Add a field to the Portfolio tab to activate likes for Portfolio items.
	 */
	function thb_portfolio_likes_container() {
		$portfolio_options_tab = thb_theme()->getAdmin()->getMainPage()->getTab( 'portfolio' );

		if ( $portfolio_options_tab ) {
			$thb_container = $portfolio_options_tab->getContainer( 'single_work_options' );

				$thb_field = new THB_CheckboxField( 'thb_portfolio_likes_active' );
				$thb_field->setLabel( __( 'Activate likes for Portfolio items', 'thb_text_domain' ) );

			$thb_container->addField( $thb_field );
		}
	}

	add_action( 'wp_loaded', 'thb_portfolio_likes_container' );
}

if( !function_exists('thb_trip_itinerary') ) {
	function thb_trip_itinerary() {
		thb_get_template_part( 'partials/partial-itinerary-nav' );
	}
}

if ( ! function_exists( 'thb_disable_blocks' ) ) {
	function thb_disable_blocks() {
		thb_builder_instance()->getBlock( 'thb_radial_chart' )->deactivate();
		thb_builder_instance()->getBlock( 'thb_testimonial' )->deactivate();
		thb_builder_instance()->getBlock( 'thb_google_map' )->deactivate();
	}

	add_action( 'wp_loaded', 'thb_disable_blocks' );
}

if( !function_exists('thb_voyager_bundled_fonts') ) {
	function thb_voyager_bundled_fonts( $fonts ) {
		$fonts['wc_mano_negra_btaregular'] = array(
			'family'   => 'Wc Mano Negra Bta Regular',
			'variants' => 'normal',
			'type'     => 'custom',
			'folder'   => get_template_directory_uri() . '/css/bundled_fonts/wcmanonegrabta.css',
			'bundle'   => true
		);

		return $fonts;
	}
}

add_filter( 'thb_get_customfonts', 'thb_voyager_bundled_fonts' );

function thb_voyager_thb_page_title_class( $class ) {
	$class .= ' entry-title';

	return $class;
}

add_filter( 'thb_page_title_class', 'thb_voyager_thb_page_title_class' );

function thb_voyager_title() {
	add_theme_support( 'title-tag' );
}

add_action( 'after_setup_theme', 'thb_voyager_title' );