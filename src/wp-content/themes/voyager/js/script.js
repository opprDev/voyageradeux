(function($) {
	"use strict";

	/**
	 * Trip map.
	 */
	$.fn.tripMap = function( options ) {
		options = $.extend( {
			"zoom": 12,
			"mapTypeId": google.maps.MapTypeId.ROADMAP,
			"center": [ 10, 10 ],
			"styles": [],
			"markers": [], // { latitude: '', longitude: '', title: '', marker: '' }
			"itinerary_enabled": false,
			"itinerary_geodesic": false,
			"itinerary_strokeColor": '#FFFFFF',
			"itinerary_strokeOpacity": 1.0,
			"itinerary_strokeWeight": 10,
			"markerClickCallback": function( marker, info, map, index, instance ) {
				var visible_index = 0;

				$.each( instance._markers, function( i ) {
					if ( instance._markers[i].visible === true ) {
						instance._markers[i].visible_index = visible_index;
					}
					else {
						instance._markers[i].visible_index = false;
					}

					visible_index++;
				} );

				if ( marker.visible_index !== false && $( "#stop-" + marker.visible_index ).length ) {
					$.scrollTo( $( "#stop-" + marker.visible_index ).position().top, 350, "easeInOutCubic" );
				}
			},
			"markerOutCallback": function( marker, info, map, index, instance ) {
				info.close();
			},
			"markerHoverCallback": function( marker, info, map, index, instance ) {
				$.each( instance._markers, function( i ) {
					if ( instance._infos[i] !== undefined ) {
						instance._infos[i].close();
					}
				} );
				info.open( map, marker );
			}
		}, options );

		return this.each( function() {
			var self = $( this ),
				map = this;

			self._markers = [];
			self._infos = [];

			self.init = function() {
				var is_mobile = $( "body" ).hasClass( "thb-mobile" ),
					center = new google.maps.LatLng( options.center[0], options.center[1] ),
					map_options = {
						"zoom": options.zoom,
						"mapTypeId": options.mapTypeId,
						"center": center,
						"styles": options.styles,
						"scrollwheel": false,
						"draggable": is_mobile ? false : true,
						"panControl": true,
						"panControlOptions": {
							position: google.maps.ControlPosition.RIGHT_BOTTOM
						},
						"zoomControlOptions": {
							position: google.maps.ControlPosition.LEFT_BOTTOM
						},
						// "disableDefaultUI": true
					};

				if ( options.itinerary_enabled ) {
					map_options.geodesic = options.itinerary_geodesic;
					map_options.strokeColor = options.itinerary_strokeColor;
					map_options.strokeOpacity = options.itinerary_strokeOpacity;
					map_options.strokeWeight = options.itinerary_strokeWeight;
				}

				self.map = new google.maps.Map( map, map_options );

				$('div').on('touchstart', '.gmnoprint div[title^=Pan]', function () {
					$(this).trigger('click');
					return false;
				});

				$( window ).on( "thbShowMap", function() {
					google.maps.event.trigger( self.map, 'resize' );
					// $( window ).trigger( "resize" );
				} );

				$( window ).on( "resize", function() {
					self.map.setCenter( center );
				} );

				self.placeMarkers();

				if ( options.itinerary_enabled ) {
					self.makeItinerary();
				}
			};

			self.placeMarkers = function() {
				$.each( options.markers, function( index, marker ) {
					var latlong = new google.maps.LatLng( marker["latitude"], marker["longitude"] ),
						marker = new google.maps.Marker( {
							position: latlong,
							map: self.map,
							title: marker["title"],
							animation: google.maps.Animation.DROP,
							icon: marker["marker"],
							visible: marker["visible"]
						} );

						if ( marker["title"] != "" ) {
							var info = new InfoBox( {
								content: marker["title"],
								disableAutoPan: false,
								maxWidth: 150,
								pixelOffset: new google.maps.Size(-120, 6),
								zIndex: null,
								boxStyle: {
									width: "240px"
								},
								infoBoxClearance: new google.maps.Size(1, 1)
							} );

							// var info = new google.maps.InfoWindow({
							// 	content: '<div class="content">' + marker["title"] + '</div>'
							// });

							self._infos.push( info );

							google.maps.event.addListener( marker, 'mouseover', function() {
								options.markerHoverCallback( this, info, self.map, index, self );
							} );

							google.maps.event.addListener( marker, 'mouseout', function() {
								options.markerOutCallback( this, info, self.map, index, self );
							} );
						}

						self._markers.push( marker );

						google.maps.event.addListener( marker, 'click', function() {
							options.markerClickCallback( this, info, self.map, index, self );
						} );
				} );
			};

			self.makeItinerary = function() {
				var itinerary = [];

				$.each( options.markers, function( index, stop ) {
					itinerary.push( new google.maps.LatLng( stop["latitude"], stop["longitude"] ) );
				} );

				var itineraryPath = new google.maps.Polyline( {
					path: itinerary,
					geodesic: options.itinerary_geodesic,
					strokeColor: options.itinerary_strokeColor,
					strokeOpacity: options.itinerary_strokeOpacity,
					strokeWeight: options.itinerary_strokeWeight
				} );

				itineraryPath.setMap( self.map );
			};

			self.init();
		} );
	};

	// -------------------------------------------------------------------------
	// -------------------------------------------------------------------------
	// -------------------------------------------------------------------------

	function mapTrigger() {
		var triggerOpen = $('#thb-show-map'),
			triggerClose = $('#thb-hide-map');

		function openMap() {
			$.thb.transition('.thb-slideshow', function() {
				$( window ).trigger( "thbShowMap" );
				if ( $( 'body' ).hasClass( 'page-layout-d' ) || $( 'body' ).hasClass( 'page-layout-f' ) || $( 'body' ).hasClass( 'page-layout-g' ) ) {
					$('#thb-page-header').css("visibility", "hidden");
				}
			});

			$('#thb-trip-map').css("visibility", "visible");
			$('body').addClass('thb-show-map');
		}

		function closeMap() {
			if ( $( 'body' ).hasClass( 'page-layout-d' ) || $( 'body' ).hasClass( 'page-layout-f' ) || $( 'body' ).hasClass( 'page-layout-g' ) ) {
				$('#thb-page-header').css("visibility", "visible");
			}
			$('body').removeClass('thb-show-map');

			$.thb.transition('.thb-slideshow', function() {
				$('#thb-trip-map').css("visibility", "hidden");
				$( window ).trigger( "thbHideMap" );
			});
		}

		triggerOpen.bind('click', function(){
			if( $("body").hasClass("thb-show-map") ) {
				closeMap();
			} else {
				openMap();
			}
			return false;
		});

		triggerClose.bind('click', function(){
			closeMap();
			return false;
		});
	}

	$(document).ready(function() {
		/**
		 * Single trip.
		 */
		if ( $( "body.single-trip" ).length ) {
			if ( typeof google === "undefined" ) {
			  return;
			}

			var options = {
				"styles": $.parseJSON( trip_stops.styles ),
				"zoom": parseInt( trip_stops.zoom, 10 ),
				"mapTypeId": google.maps.MapTypeId[trip_stops.type],
				"itinerary_enabled": parseInt( trip_stops.itinerary, 10 ),
				"itinerary_geodesic": parseInt( trip_stops.geodesic, 10 ),
				"itinerary_strokeColor": trip_stops.strokeColor,
				"itinerary_strokeOpacity": trip_stops.strokeOpacity,
				"itinerary_strokeWeight": trip_stops.strokeWeight,
				"center": trip_stops.center,
				"markers": []
			};

			if ( trip_stops.stops.length > 0 ) {
				$.each( trip_stops.stops, function( index, stop ) {
					// if ( stop.latitude == "" || stop.longitude == "" ) {
					// 	options.itinerary_enabled = false;

					// 	return;
					// }

					if ( index == 0 && options.center.length == 0 ) {
						options.center = [ stop.latitude, stop.longitude ];
					}

					options.markers.push( {
						"latitude": stop.latitude,
						"longitude": stop.longitude,
						"title": stop.label,
						"marker": stop.marker,
						"visible": stop.show_on_map
					} );
				} );
			}
			else {
				options.itinerary_enabled = false;
			}

			// Map
			if ( $( "#thb-trip-map" ).length ) {
				$( "#thb-trip-map" ).tripMap( options );
			}

			// Map trigger
			mapTrigger();

			// Fitted stops
			$( ".thb-trip-stop.thb-trip-stop-fit" ).each( function() {
				var offset = $( "body" ).offset().top,
					window_height = $( window ).height(),
					w_height = window_height - offset;

				$( this ).css( "min-height", w_height );
			} );

			// Stop gallery trigger
			$( ".thb-trip-view-gallery" ).on( "click", function() {
				var stop_id = $( this ).data( "stop" );

				var pictures = [];

				$.each( trip_stops.pictures[stop_id], function() {
					pictures.push( {
						src: this
					} );
				} );

				if ( typeof jQuery.magnificPopup != 'undefined' ) {
					$.magnificPopup.open({
						items: pictures,
						type: 'image',
						gallery: {
							enabled: true
						},
						removalDelay: 200,
						mainClass: 'thb-mfp-skin'
					}, 0);
				}

				return false;
			} );

		}

		/**
		 * Photosets.
		 */
		$( ".thb-photoset-grid" ).each( function() {
			var photoset = $( this );

			$.thb.loadImage( photoset, {
				allLoaded: function() {
					photoset.photosetGrid({
						gutter: photoset.data( "gutter" ) !== undefined ? photoset.data( "gutter" ) : 0,
						onComplete: function() {
							if ( typeof $.magnificPopup != 'undefined' ) {
								var lightbox = photoset.data( "lightbox" ) !== undefined ? parseInt( photoset.data( "lightbox" ), 10 ) : 1;

								if ( lightbox ) {
									photoset.find("img").magnificPopup({
										type: 'image',
										gallery: {
											enabled: true
										},
										removalDelay: 200,
										mainClass: 'thb-mfp-skin'
									});
								}
							}
						}
					});
				}
			} );
		} );

		/**
		 * Side nav menu
		 */
		$( "#slide-menu-container .menu li.menu-item-has-children" )
			.append("<span class='trigger'></span>");

		$( "#slide-menu-container .menu li.menu-item-has-children .trigger" )
			.on( "click", function() {
				var submenu = $( this ).parent().find( "> ul.sub-menu" );

				if ( submenu.hasClass( "open" ) ) {
					submenu.removeClass( "open" );
					submenu.find( "ul" ).removeClass( "open" );
				}
				else {
					submenu.addClass( "open" );
				}

				return false;
			} );

		/**
		 * Main nav toggle
		 */
		function menuToggle() {
			var triggerOpen = $('#thb-trigger-open'),
				triggerClose = $('#thb-trigger-close');

			function openMenu() {
				$('#slide-menu-container').css("visibility", "visible");
				$('body').addClass('menu-open');
			}

			function closeMenu() {
				$('body').removeClass('menu-open');
				$.thb.transition('#slide-menu-container', function() {
					$('#slide-menu-container').css("visibility", "hidden");
				});
			}

			triggerOpen.bind('click', function(){
				if( $("body").hasClass("menu-open") ) {
					closeMenu();
				} else {
					openMenu();
				}
				return false;
			});

			triggerClose.bind('click', function(){
				closeMenu();
				return false;
			});

			$.thb.key("esc", function() {
				if( $("body").hasClass("menu-open") ) {
					closeMenu();
				}
			});
		}

		menuToggle();

		/**
		 * Scroll in page
		 */

		if( ! $('body').hasClass('thb-mobile') ) {
			var smoothScrollSelectors = ".thb-btn.action-primary, .thb-btn.action-secondary, li.menu-item a, .thb-slide-caption .thb-call-to .thb-btn";

			window.thb_scroll_in_page( smoothScrollSelectors );
		}

		/**
		 * Go top
		 */

		if( ! $('body').hasClass('thb-mobile') ) {
			$(window).scroll(function(){
				if ($(this).scrollTop() > 300) {
					$('.thb-scrollup').fadeIn('fast');
				} else {
					$('.thb-scrollup').fadeOut('fast');
				}
			});
		}

		if ( $('.thb-go-top').length ) {
			$('.thb-go-top').click(function(){
				$("html, body").stop().animate({ scrollTop: 0 }, 350, 'easeInOutCubic' );
				return false;
			});
		}

		/**
		 * Menu
		 */

		if ( ! $( "body" ).hasClass( "header-layout-b" ) ) {
			$(".main-navigation > div").menu({
				megaMenu: {
					fixed: true
				}
			});
		}

		/**
		 * FitVids
		 */

		$(".thb-text, .textwidget, .work-slides-container, .format-embed-wrapper, .thb-section-block-thb_video-video-holder").fitVids();

		if ( $( "body" ).hasClass( "thb-desktop") ) {
			$( ".thb-trip-stop-background-appearance-parallax .thb-trip-image-holder" ).thbParallax();
		}

		/**
		 * Fit builder section height to the window height
		 */
		if ( $( ".thb-section" ).length ) {
			$( ".thb-section-extra[data-fit-height='1']" ).each( function() {
				var section = $( this ),
					offset = $( "body" ).offset().top,
					window_height = $( window ).height();

					var w_height = window_height - offset;

				section.css('min-height', w_height );
			} );
		}

		/**
		 * Sections
		 */

		if ( $( "body" ).hasClass( "thb-desktop") ) {
			if ( $( ".thb-section" ).length ) {
				$( ".thb-section-extra[data-parallax='1']" ).each( function() {
					var section = $( this ),
						background_image = section.css( "background-image" ).replace( "url(", "" ).replace( ")", "" );

					if ( background_image == 'none' ) {
						return;
					}

					section.thbParallax();

					// section.parallax('50%', 0.6);
				} );
			}
		}

		/**
		 * Add a page preload
		 */

		if( ! $('body').hasClass('thb-mobile') ) {
			NProgress.configure().start();

			$( ".thb-header-section-inner-wrapper header" ).imagesLoaded( function() {

				var offset = $( "body" ).offset().top,
					window_height = $( window ).height(),
					w_height = window_height - offset,
					logo = $( "#logo" ),
					header = $( ".thb-header-section-inner-wrapper header");

				if ( ! $( 'body' ).hasClass( 'thb-sticky-header' ) && ! $( 'body').hasClass( 'page-layout-g') ) {
					header.css( 'min-height', logo.outerHeight() );
				} else {
					if ( ! $('body').hasClass( 'page-layout-g') ) {
						$( '#thb-main-external-wrapper' ).css( 'padding-top', $( '.thb-header-section-inner-wrapper header' ).outerHeight() );
					}
				}

				if ( ! $( "body" ).hasClass( "page-layout-g" ) && ( $( "body" ).hasClass( "thb-pageheader-parallax" ) && $( "body" ).hasClass( "thb-desktop") ) ) {
					// $(".thb-page-header-image-holder .full_slideshow .slide").parallax("50%",-0.6);
					$(".thb-page-header-image-holder .full_slideshow .slide").thbParallax( { speed: -0.6 } );
				}

				if ( $( "body" ).hasClass( "page-layout-e" ) || $( "body" ).hasClass( "page-layout-f" ) ) {
					if ( $( 'body' ).hasClass( 'thb-sticky-header' ) ) {
						w_height = w_height - $( '.thb-header-section-inner-wrapper header' ).outerHeight();
					}

					// Disabled since we've added a 100vh in the main css stylesheet

					// $( ".thb-page-header-image-holder, .thb-page-header-image-holder .full_slideshow" ).css( "height", w_height );

					// if ( $( "body" ).hasClass( "page-layout-f" ) ) {
						// $( ".thb-page-header-wrapper" ).css( "height", w_height );
					// }
				}

				/**
				 * Fix the content height if there isn't enough content
				 */

				if ( $('#thb-page-content').length > 0 ) {
					var body_height = $('body').height(),
						container = $( "#thb-main-external-wrapper" ),
						container_height = container.outerHeight(),
						w_h = $(window).height() - $('body').offset().top,
						page_content_height = $('#thb-page-content').outerHeight(),
						body_window_diff = w_h - body_height;

					if ( ! $('body').hasClass( 'page-layout-g') ) {
						if ( body_height < w_h ) {
							$('#thb-page-content').css( 'min-height', page_content_height + body_window_diff );
						}
					} else {
						if( $( "body" ).hasClass( "thb-desktop" ) && $.thb.wp.is_larger_than( 990 ) ) {
							w_h = $(window).height() - container.offset().top;

							if ( $( "thb-inner-wrapper" ).outerHeight() < w_h ) {
								$('#thb-page-content').css( 'min-height',  page_content_height + (w_h - $( "#thb-inner-wrapper" ).outerHeight()) );
							}
						}
					}
				}

				setTimeout( function() {
					$( window ).trigger( "resize" );

					NProgress.done();
					setTimeout( function() {
						$("body").addClass("thb-page-loaded");
					}, 250 );
				}, 250 );
			} );
		} else {
			$("body").addClass("thb-page-loaded");
		}

		/**
		 * Blog Masonry
		 */

		if( $( ".thb-masonry-container" ).length ) {
			var blog_masonry = new THB_Isotope( $(".thb-masonry-container") );
		}

		/**
		 * Photogallery.
		 */
		window.thb_isotope_styleAdjust = function() {};

		/**
		 * Portfolio.
		 */
		if ( $( ".thb-portfolio" ).length ) {
			$( ".thb-portfolio" ).each( function() {
				var portfolio = $( this ),
					useAjax = portfolio.attr( "data-ajax" ) == "1",
					isotopeContainer = $( ".thb-grid-layout", portfolio ),
					filter_controls = $( ".filterlist", portfolio ),
					portfolio_pagination = $( ".thb-navigation", portfolio ),
					thb_portfolio_filtering = false;

				if( ! useAjax ) {
					$( "li", filter_controls ).each(function() {
						var data = $(this).data("filter");

						if( data !== "" ) {
							if( ! isotopeContainer.find("[data-filter-" + data + "]").length ) {
								$(this).remove();
							}
						}
					});
				}

				var portfolio_isotope = new THB_Isotope( isotopeContainer, {
					filter: new THB_Filter(isotopeContainer, {
						controls: filter_controls,
						controlsOnClass: "active",
						filter: function( selector ) {

							if ( ! useAjax ) {
								portfolio_isotope.filter( selector );
							}
						}
					})
				});

				isotopeContainer.data( "thb_isotope", portfolio_isotope );

				window.thb_portfolio_reload = function( url, portfolio, callback ) {
					var portfolio_pagination = $( ".thb-navigation", portfolio ),
						isotopeContainer = $( ".thb-grid-layout", portfolio ),
						index = portfolio.index( $( ".thb-portfolio" ) );

					isotopeContainer.data( "thb_isotope" ).remove(function() {
						$.thb.loadUrl(url, {
							method: "POST",
							filter: false,
							complete: function( data ) {
								var target_portfolio = $(data).find( ".thb-portfolio" ).eq( index );

								NProgress.done();
								var items = target_portfolio.find(".thb-grid-layout .item");

								if( portfolio_pagination.length ) {
									if ( target_portfolio.find(".thb-navigation").length ) {
										portfolio_pagination.replaceWith( target_portfolio.find(".thb-navigation") );
									} else {
										portfolio_pagination.html('');
									}
								}
								else {
									isotopeContainer.after( target_portfolio.find(".thb-navigation") );
								}

								isotopeContainer.data( "thb_isotope" ).insert(items, function() {
									thb_portfolio_bind_pagination( portfolio );

									if( callback !== undefined ) {
										callback();
									}
								});
							}
						});
					});
				};

				window.thb_portfolio_bind_pagination = function( portfolio ) {
					$( ".thb-navigation a", portfolio ).on("click", function() {
						NProgress.configure().start();
						thb_portfolio_reload( $(this).attr("href"), portfolio, function() {
							$( window ).trigger( "resize" );
						} );
						return false;
					});
				};

				window.thb_portfolio_bind_filter = function( portfolio ) {
					var filter_controls = $( ".filterlist", portfolio );

					$( "li", filter_controls ).on("click", function() {
						if( thb_portfolio_filtering ) {
							return false;
						}

						thb_portfolio_filtering = true;

						thb_portfolio_reload( $(this).data("href"), portfolio, function() {
							thb_portfolio_filtering = false;
							$( window ).trigger( "resize" );
						} );

						NProgress.configure().start();

						$( "li", filter_controls ).removeClass("active");
						$(this).addClass("active");
						return false;
					});
				};

				if( useAjax ) {
					thb_portfolio_bind_filter( portfolio );
					thb_portfolio_bind_pagination( portfolio );
				}
			} );
		}

		/**
		 * Slideshow
		 */
		if ( $( '.thb-slideshow, .thb-work-slideshow, .thb-section' ).length ) {
			var rsOptions = {
				loop: true,
				slidesSpacing: 0,
				navigateByClick: false,
				addActiveClass: true,
				imageScaleMode: "fill",
				numImagesToPreload: 1,
				keyboardNavEnabled: true,
				video: {
					youTubeCode: '<iframe src="//www.youtube.com/embed/%id%?rel=1&showinfo=0&autoplay=1&wmode=transparent" frameborder="no"></iframe>',
					vimeoCode: '<iframe src="//player.vimeo.com/video/%id%?byline=0&amp;portrait=0&amp;autoplay=1" frameborder="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
				}
			};

			window.thb_slide_skin_class = function( slide ) {

				var selector = "#thb-header header";

				if ( ! $( 'body' ).hasClass( 'pageheader-layout-a' ) && ! $( 'body' ).hasClass( 'pageheader-layout-b' ) && ! $( 'body' ).hasClass( 'page-layout-g' ) ) {
					selector += ", .thb-main-nav-wrapper";
				}

				$( selector ).removeClass( "thb-skin-light thb-skin-dark thb-skin-" );

				if ( slide.hasClass( "thb-skin-light" ) ) {
					$( selector ).addClass( "thb-skin-light" );
					window.skin_light_class = true;
					window.skin_dark_class = false;
				}
				else {
					$( selector ).addClass( "thb-skin-dark" );
					window.skin_light_class = false;
					window.skin_dark_class = true;
				}
			};

			$( '.thb-slideshow' ).each( function() {

				var thb_slideshow_container = $( this ),
					isMainSlideshow = thb_slideshow_container.hasClass( 'thb-main-slideshow' ),
					hasMultipleSlides = thb_slideshow_container.find( ".slide" ).length > 1,
					hasImages = thb_slideshow_container.find( "img" ).length > 0;

				// Defaults

				if ( isMainSlideshow && window.thb_slideshow ) {
					rsOptions.transitionType = window.thb_slideshow.effect;
				}

				if ( ! hasMultipleSlides ) {
					rsOptions.transitionType = "fade";
					rsOptions.controlNavigation = 'none';
				}

				// Autoplay

				if ( isMainSlideshow && window.thb_slideshow ) {
					if ( window.thb_slideshow.autoplay && window.thb_slideshow.autoplay == "1" ) {
						rsOptions.autoPlay = {
							enabled: true,
							delay: window.thb_slideshow.speed
						};
					}
				}
				else {
					if ( thb_slideshow_container.hasClass( 'thb-section-slideshow' ) ) {
						rsOptions.keyboardNavEnabled = false;
						rsOptions.transitionType = "fade";
					}
				}

				if ( thb_slideshow_container.hasClass( 'page-content-slideshow' ) ) {
					rsOptions.autoScaleSlider = true;
					rsOptions.autoScaleSliderWidth = 930;
					rsOptions.autoScaleSliderHeight = 523;
				}

				thb_slideshow_container.on( "thbSetupSlide", function( event, thb_slideshow_container, slide ) {
					/**
					 * Skin
					 */
					thb_slide_skin_class( slide );

					if ( $( "body" ).hasClass( "thb-desktop") ) {
						if ( thb_slideshow_container.hasClass( 'thb-main-slideshow' ) && $( "body" ).hasClass( "thb-pageheader-parallax" ) ) {
							// slide.parallax("50%",-0.6);
							slide.thbParallax( { speed: -0.6 } );
						}
						else if ( slide.parents( ".thb-trip-stop-background-appearance-parallax" ).length ) {
							// slide.parallax("50%",-0.6);
							slide.thbParallax( { speed: -0.6 } );
						}
					}

					if ( thb_slideshow_container.hasClass('rsFade') ) {
						$( window ).trigger('resize');
					}
				} );

				thb_slideshow_container.thbRoyalSliderSlideshow( rsOptions );
				// thb_slideshow_start(thb_slideshow_container, rsOptions);
			} );
		}

		/**
		 * Galleries
		 */

		$(".masonry .thb-gallery").each( function() {
			$( this ).royalSlider({
				loopRewind: true,
				slidesSpacing: 0,
				navigateByClick: false,
				imageScaleMode: "fill",
				autoScaleSlider: true,
				autoScaleSliderWidth: 300,
				autoScaleSliderHeight: 300,
				numImagesToPreload: parseInt( $( this ).data( "count" ), 10 ),
				video: {
					youTubeCode: '<iframe src="//www.youtube.com/embed/%id%?rel=1&showinfo=0&autoplay=1&wmode=transparent" frameborder="no"></iframe>',
				}
			});
		} );

		$(".classic .thb-gallery, .single-post .thb-gallery").each( function() {
			$( this ).royalSlider({
				loopRewind: true,
				slidesSpacing: 0,
				navigateByClick: false,
				imageScaleMode: "fill",
				autoScaleSlider: true,
				autoScaleSliderWidth: 930,
				autoScaleSliderHeight: 523,
				numImagesToPreload: parseInt( $( this ).data( "count" ), 10 ),
				video: {
					youTubeCode: '<iframe src="//www.youtube.com/embed/%id%?rel=1&showinfo=0&autoplay=1&wmode=transparent" frameborder="no"></iframe>',
				}
			});
		} );

		/**
		 * Work slideshow
		 */
		if ( $( '.thb-work-slideshow' ).length ) {
			$( '.thb-work-slideshow' ).thbRoyalSliderSlideshow( {
				autoHeight: true,
				autoScaleSlider: false,
				arrowsNav: true,
				fadeinLoadedSlide: false,
				controlNavigationSpacing: 0,
				imageScaleMode: 'none',
				imageAlignCenter: false,
				controlNavigation: 'none',
				// loop: false,
				loopRewind: true,
				numImagesToPreload: 1,
				keyboardNavEnabled: true,
				// usePreloader: false,
				loop: true,
				slidesSpacing: 0,
				navigateByClick: false,
				addActiveClass: true,
				video: {
					youTubeCode: '<iframe src="//www.youtube.com/embed/%id%?rel=1&showinfo=0&autoplay=1&wmode=transparent" frameborder="no"></iframe>',
				}
			} );
		}

	});

	/**
	 * Fast click
	 */

	if( $( "body" ).hasClass( "thb-mobile" ) ) {
		var attachFastClick = Origami.fastclick;
			attachFastClick(document.body);
	}

})(jQuery);