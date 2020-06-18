// *****************************************************
/**
 *
 */
function validation( element, regEx, message )
{
	ok = false;
	if( !$( '#' + $( element ).attr( 'id' ) ).is( ':disabled' ) )
	{
		if( !regEx.test( $( element ).val() ) )
		{
			$( '#' + $( element ).attr( 'id' ) + '_exception' ).html( message );
			$( '#' + $( element ).attr( 'id' ) ).css( 'border-color', '#F00' );
		}
		else
		{
			$( '#' + $( element ).attr( 'id' ) + '_exception' ).html( '' );
			$( '#' + $( element ).attr( 'id' ) ).css( 'border-color', '#BBB' );
			ok = true;
		}
	}
	return ok;
}

// *****************************************************
/**
 *
 */
$( function()
{
	if( $( '#event_selector' ).val() < 0 )
	{
		$( "#event_time" ).prop( 'disabled', true );
		$( "#event_name" ).prop( 'disabled', true );
	}
	else
	{
		$( "#event_time" ).prop( 'disabled', false );
		$( "#event_name" ).prop( 'disabled', false );
	}

// *****************************************************
/**
 *
 */
	$( '#event_selector' ).on( 'change', function()
	{
		if( $( this ).val() < 0 )
		{
			$( '#event_selector_exception' ).html( "Velg en øvelse!" );
			$( '#event_selector' ).css( 'border-color', '#F00' );
			$( "#event_time" ).prop( 'disabled', true );
			$( "#event_name" ).prop( 'disabled', true );
		}
		else
		{
			$( '#event_selector_exception' ).html( '' );
			$( '#event_selector' ).css( 'border-color', '#BBB' );
			$( "#event_time" ).prop( 'disabled', false );
			$( "#event_name" ).prop( 'disabled', false );
		}

		$( '#event_time_exception' ).html( '' );
		$( '#event_time' ).css( 'border-color', '#BBB' );

		$( '#event_name_exception' ).html( '' );
		$( '#event_name' ).css( 'border-color', '#BBB' );

		$.ajax(
		{
			url: "get_event_data.ajax.php",
			data:
			{
				event_ID: $( this ).val()
			},
			type: 'GET',
			dataType: "json",
			success: function( json )
			{
				$( "#event_time" ).val( json.event_time );
				$( "#event_name" ).val( json.event_name );
			}
		});
	});

// *****************************************************
/**
 *
 */	
	$( '#event_time' ).on( 'change', function()
	{
		validation( this, /^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/, 'Ugyldig tidspunkt!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#event_name' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ0-9\.\' \-]{2,45}$/, 'Ugyldig navn på øvelse!' );
	});

// *****************************************************

});

// *****************************************************
