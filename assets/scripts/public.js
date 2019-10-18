( function() {


	if ( 'underfined' !== typeof( pstuMisspellings ) ) {

		var $body = jQuery( 'body' );
		var $notice = jQuery( '.' + pstuMisspellings.slug + '_button' );
		var $modal = jQuery( '#' + pstuMisspellings.slug + '_modal' );
		var $form = jQuery( '#' + pstuMisspellings.slug + '_form' );
		var $answer = jQuery( '<div>', {
				'id': pstuMisspellings.slug + '_answer',
				'style': 'display: none;'
			} ).appendTo( $body );
		var modal_flag = false;



		function getSelectedText () {
			var text = "";
			if ( window.getSelection ) {
				text = window.getSelection();
			} else if ( document.getSelection ) {
				text = document.getSelection();
			} else if ( document.selection ) {
				text = document.selection.createRange().text;
			}
			return text;
		}



		function open() {
			var $button = jQuery( this ),
				error = getSelectedText();
			if ( ! modal_flag ) {
				jQuery.fancybox.open( {
					src: '#' + $modal.attr( 'id' ),
					beforeShow: function() {
						modal_flag = true;
						form_open = true;
						$form.find( '#' + pstuMisspellings.slug + '_error' ).val( error );
					},
					afterClose: function() {
						modal_flag = false;
					}
				} );
			}
		}






		function message() {
			var query = {};
			var mask = pstuMisspellings.slug + '_';
			var $fields = $form.find( '[name^='+ mask +']' );
			$fields.each( function ( index, field ) {
				query[ jQuery( field ).attr( 'name' ).replace( mask, '' ) ] = jQuery( field ).val();
			} );
			return query;
		}



		function answer( content ) {
			if ( ! modal_flag ) {
				$answer.text( content );
				jQuery.fancybox.open( {
					src: '#' + $answer.attr( 'id' ),
					beforeShow: function() {
						modal_flag = true;
					},
					afterClose: function() {
						modal_flag = false;
					}
				} );
			}
		}



		function send() {
			event.preventDefault();
			jQuery.ajax( {
				type: pstuMisspellings.method,
				url: pstuMisspellings.ajaxurl,
				data: {
					action: pstuMisspellings.slug,
					query: message(),
					security: pstuMisspellings.security
				},
				beforeSend: function( xhr ) {
					jQuery.fancybox.close();
				},
				success: function( data ) {
					if ( 'underfined' != typeof( data.success ) && data.success ) {
						$form.find( 'input:not( [type=submit] ):not([readonly]), textarea' ).val( '' );
						answer( pstuMisspellings.success );
					} else {
						answer( pstuMisspellings.error + ' ' + data.data );
					}
				},
				error: function( data ) {
					answer( pstuMisspellings.error );
				}
			} );
		}



		function shortkeys( e ) {
			if ( e.ctrlKey && e.which == 13 ) {		
				open();
			}
		}



		$notice.append( jQuery( '<span>', {
			class: 'title',
			text: pstuMisspellings.notice
		} ) );
		$notice.on( 'click', open );
		$form.on( 'submit', send );
		$body.on( 'keyup', shortkeys );
		



	}


} )();