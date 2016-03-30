( function( $ ) {

	/**
	 * Sync trip with its stops in the post editing screen.
	 */
	window.thb_sync_trip_stops = function( value ) {
		var stops_select = $( "select[name='trip_stop']" );
		stops_select.html( "" );

		$.each( voyager_trips, function() {
			if ( this.id == value ) {
				$.each( this.stops, function() {
					stops_select.append( "<option value='" + this.value + "'>" + this.label + "</option>" );
				} );
			}
		} );

		stops_select.trigger( "render" );
	};

	/**
	 * Add a new stop to the trip.
	 */
	window.thb_voyager_trip_add_stop = function( container, tpl, control ) {
		var stop_add_modal = new THB_Modal( 'voyager_trip_stop', control.text(), function( data, modal ) {
			var field = container.addField( tpl ),
				input = $( "input.thb-stop-value", field ),
				edit_button = $( ".thb-stop-edit", field );

			input.val( $.param( data ) );

			var label = data["label"].trim() == "" ? edit_button.data( "default-label" ) : data["label"].trim();
			edit_button.html( label );

			$( '.tt[title]', field ).tipTop();

			return false;
		} );

		stop_add_modal.open( {} );
	};

	/**
	 * Boot the trip stop field.
	 */
	window.thb_voyager_trip_stop_field = function( $el ) {
		var input = $( "input.thb-stop-value", $el ),
			edit_button = $( ".thb-stop-edit", $el );

		edit_button.on( "click", function() {
			var stop_edit_modal = new THB_Modal( 'voyager_trip_stop', edit_button.text(), function( data, modal ) {
				input.val( $.param( data ) );

				var label = data["label"].trim() == "" ? edit_button.data( "default-label" ) : data["label"].trim();
				edit_button.find("a").html( label );

				return false;
			} );

			stop_edit_modal.open( $.deparam( input.val() ) );
			return false;
		} );
	};

	/**
	 * Boot the latitude & longitude field.
	 */
	window.thb_voyager_latlong_field = function( $el ) {
		var latlong_field = this,
			input = $el.find( ".thb-latlong-value" ),
			input_search = $el.find( ".thb-latlong-search" ).get( 0 ),
			map = $el.find( ".thb-latlong-map" ).get( 0 ),
			form = $el.parents( "form" );

		latlong_field.center_marker = null;

		var get_map_center = function() {
			if ( input.val() != '' ) {
				var latlong = input.val().split(",");

				if ( latlong[0] && latlong[1] ) {
					return new google.maps.LatLng( latlong[0], latlong[1] );
				}
			}

			return new google.maps.LatLng( 51.48257659999999, -0.007658900000024005 );
		};

		var latlong_map = new google.maps.Map( map, {
			"zoom": 16,
			"mapTypeId": google.maps.MapTypeId.ROADMAP,
			"streetViewControl": false,
			"center": get_map_center()
		} )

		var reposition_marker = function( position ) {
			if ( latlong_field.center_marker ) {
				latlong_field.center_marker.setMap( null );
			}

			latlong_field.center_marker = new google.maps.Marker( {
				position: position,
				map: latlong_map,
				draggable: true,
			} );

			google.maps.event.addListener( latlong_field.center_marker, 'dragend', function( event ) {
				var latlong = latlong_field.center_marker.getPosition();
			    input.val( latlong.lat() + "," + latlong.lng() );
			} );
		};

		$( window ).on( "resize", function() {
			google.maps.event.trigger( latlong_map, 'resize' );
			reposition_marker( get_map_center() );
			latlong_map.setCenter( get_map_center() );
		} );

		input.on( "change keyup", function() {
			reposition_marker( get_map_center() );
			latlong_map.setCenter( get_map_center() );
		} );

		$( input_search ).on( "keydown", function( e ) {
			if ( e.which == 13 ) {
				return false;
			}
		} );

		latlong_map.controls[google.maps.ControlPosition.TOP_LEFT].push( input_search );

		var searchBox = new google.maps.places.SearchBox( input_search );

		google.maps.event.addListener( searchBox, 'places_changed', function() {
		    var places = searchBox.getPlaces();

		    if ( places.length == 0 ) {
				return false;
		    }

		    reposition_marker( places[0].geometry.location );
		    input.val( places[0].geometry.location.lat() + "," + places[0].geometry.location.lng() );
		    latlong_map.setCenter( get_map_center() );

			return false;
		} );

		google.maps.event.addListener( latlong_map, "click", function( event ) {
			var lat = event.latLng.lat(),
				lng = event.latLng.lng(),
				latlong = new google.maps.LatLng( lat, lng );

			reposition_marker( latlong );

			input.val( lat + "," + lng );
		} );
	};

	/**
	 * Boot fields.
	 */
	$( window ).on( "thb_boot_fields", function( event, root ) {
		if ( root.hasClass( "thb-field" ) ) {
			if ( root.hasClass( "thb-field-voyager_trip_stop" ) ) {
				thb_voyager_trip_stop_field( root );
			}
			else if ( root.hasClass( "thb-field-voyager_latlong" ) ) {
				thb_voyager_latlong_field( root );
			}
		}
		else {
			$( ".thb-field-voyager_trip_stop", root ).each( function() {
				thb_voyager_trip_stop_field( $( this ) );
			} );

			$( ".thb-field-voyager_latlong", root ).each( function() {
				thb_voyager_latlong_field( $( this ) );
			} );
		}
	} );

} )( jQuery );