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
	$( '#old_password' ).on( 'change', function()
	{
		validation( this, /^[a-zA-Z0-9]{4,20}$/, 'Ugyldig passord!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#new_password' ).on( 'change', function()
	{
		validation( this, /^[a-zA-Z0-9]{4,20}$/, 'Ugyldig passord!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#confirmed_password' ).on( 'change', function()
	{
		if( $( "#confirmed_password" ).val() !== $( "#new_password" ).val() )
		{
			$( '#' + $( this ).attr( 'id' ) + '_exception' ).html( "Passordene stemmer ikke overens!" );
			$( '#' + $( this ).attr( 'id' ) ).css( 'border-color', '#F00' );
		}
		else
		{
			$( '#' + $( this ).attr( 'id' ) + '_exception' ).html( '' );
			$( '#' + $( this ).attr( 'id' ) ).css( 'border-color', '#BBB' );
		}
	});

// *****************************************************

});
