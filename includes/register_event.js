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

// *************************************************************************
/**
 *
 */	
$( function()
{

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
