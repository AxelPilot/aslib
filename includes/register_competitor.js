// *****************************************************
/**
 *
 */
function validation( element, regEx, message )
{
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
		}
	}
}

// *************************************************************************
/**
 *
 */	
function validate_email( email )
{
	regEx = /^[a-zA-Z0-9_\.\-]*@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,4}$/;
	ok = regEx.test( email );
	return ok;
}

// *************************************************************************
/**
 *
 */	
function correct_enabled_status( email_element )
{
	$.ajax(
	{
		url: "get_competitor_data.ajax.php",
		data:
		{
			email: $( email_element ).val()
		},
		type: 'GET',
		dataType: "json",
		success: function( user )
		{
			if( !validate_email( $( email_element ).val() ) )
			{
				$( "#nationality" ).prop( 'disabled', true );
				$( "#firstname" ).prop( 'disabled', true );
				$( "#lastname" ).prop( 'disabled', true );
				$( "#address" ).prop( 'disabled', true );
				$( "#postal_code" ).prop( 'disabled', true );
				$( "#city" ).prop( 'disabled', true );
				$( "#phone" ).prop( 'disabled', true );
			}				
			else if( user.exists )
			{
				if ( typeof user.nationality === 'undefined' )
				{
					$( "#nationality" ).prop( 'disabled', false );
				}
			}
			else
			{
				$( "#nationality" ).prop( 'disabled', false );
				$( "#firstname" ).prop( 'disabled', false );
				$( "#lastname" ).prop( 'disabled', false );
				$( "#address" ).prop( 'disabled', false );
				$( "#postal_code" ).prop( 'disabled', false );
				$( "#city" ).prop( 'disabled', false );
				$( "#phone" ).prop( 'disabled', false );
			}
		}
	});
}

// *************************************************************************
/**
 *
 */	
function validate_email_ajax( email_element )
{
	$.ajax(
	{
		url: "get_competitor_data.ajax.php",
		data:
		{
			email: $( email_element ).val()
		},
		type: 'GET',
		dataType: "json",
		success: function( user )
		{
			if( !validate_email( $( email_element ).val() ) )
			{
				$( '#email_exception' ).html( 'Ugyldig e-postadresse!' );
				$( '#email' ).css( 'border-color', '#F00' );

				$( "#nationality" ).prop( 'disabled', true );
				$( "#firstname" ).prop( 'disabled', true );
				$( "#lastname" ).prop( 'disabled', true );
				$( "#address" ).prop( 'disabled', true );
				$( "#postal_code" ).prop( 'disabled', true );
				$( "#city" ).prop( 'disabled', true );
				$( "#phone" ).prop( 'disabled', true );

				$( "#nationality" ).val( '' );
				$( "#firstname" ).val( '' );
				$( "#lastname" ).val( '' );
				$( "#address" ).val( '' );
				$( "#postal_code" ).val( '' );
				$( "#city" ).val( '' );
				$( "#phone" ).val( '' );

				$( "#nationality_exception" ).html( '' );
				$( "#firstname_exception" ).html( '' );
				$( "#lastname_exception" ).html( '' );
				$( "#address_exception" ).html( '' );
				$( "#postal_code_exception" ).html( '' );
				$( "#city_exception" ).html( '' );
				$( "#phone_exception" ).html( '' );

				$( "#nationality" ).css( 'border-color', '#BBB' );
				$( "#firstname" ).css( 'border-color', '#BBB' );
				$( "#lastname" ).css( 'border-color', '#BBB' );
				$( "#address" ).css( 'border-color', '#BBB' );
				$( "#postal_code" ).css( 'border-color', '#BBB' );
				$( "#city" ).css( 'border-color', '#BBB' );
				$( "#phone" ).css( 'border-color', '#BBB' );
			}				
			else if( user.exists )
			{
				$( '#email_exception' ).html( '' );
				$( '#email' ).css( 'border-color', '#BBB' );

				if ( typeof user.nationality !== 'undefined' )
				{
					$( "#nationality" ).prop( 'disabled', true );
					$( "#nationality" ).val( user.nationality );
					
					$( "#nationality_exception" ).html( '' );
					$( "#nationality" ).css( 'border-color', '#BBB' );
				}
				else
				{
					$( "#nationality" ).val( '' );
					$( "#nationality" ).prop( 'disabled', false );
					
					$( "#nationality_exception" ).html( '' );
					$( "#nationality" ).css( 'border-color', '#BBB' );
				}

				$( "#firstname" ).prop( 'disabled', true );
				$( "#lastname" ).prop( 'disabled', true );
				$( "#address" ).prop( 'disabled', true );
				$( "#postal_code" ).prop( 'disabled', true );
				$( "#city" ).prop( 'disabled', true );
				$( "#phone" ).prop( 'disabled', true );

				$( "#firstname_exception" ).html( '' );
				$( "#lastname_exception" ).html( '' );
				$( "#address_exception" ).html( '' );
				$( "#postal_code_exception" ).html( '' );
				$( "#city_exception" ).html( '' );
				$( "#phone_exception" ).html( '' );

				$( "#firstname" ).css( 'border-color', '#BBB' );
				$( "#lastname" ).css( 'border-color', '#BBB' );
				$( "#address" ).css( 'border-color', '#BBB' );
				$( "#postal_code" ).css( 'border-color', '#BBB' );
				$( "#city" ).css( 'border-color', '#BBB' );
				$( "#phone" ).css( 'border-color', '#BBB' );

				$( "#firstname" ).val( user.firstname );
				$( "#lastname" ).val( user.lastname );
				$( "#address" ).val( user.address );
				$( "#postal_code" ).val( user.postal_code );
				$( "#city" ).val( user.city );
				$( "#phone" ).val( user.phone );
			}
			else
			{
				$( '#email_exception' ).html( '' );
				$( '#email' ).css( 'border-color', '#BBB' );

				$( "#nationality" ).val( '' );
				$( "#firstname" ).val( '' );
				$( "#lastname" ).val( '' );
				$( "#address" ).val( '' );
				$( "#postal_code" ).val( '' );
				$( "#city" ).val( '' );
				$( "#phone" ).val( '' );

				$( "#nationality" ).prop( 'disabled', false );
				$( "#firstname" ).prop( 'disabled', false );
				$( "#lastname" ).prop( 'disabled', false );
				$( "#address" ).prop( 'disabled', false );
				$( "#postal_code" ).prop( 'disabled', false );
				$( "#city" ).prop( 'disabled', false );
				$( "#phone" ).prop( 'disabled', false );
			}
		}
	});
}

// *************************************************************************
/**
 *
 */	
$( function()
{
	correct_enabled_status( $( '#email' ) );

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
		}
		else
		{
			$( '#event_selector_exception' ).html( '' );
			$( '#event_selector' ).css( 'border-color', '#BBB' );
		}
	});

// *****************************************************
/**
 *
 */	
	$( '#email' ).on( 'change', function()
	{
		validate_email_ajax( this );
	});

// *****************************************************
/**
 *
 */	
	$( '#nationality' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ\.\' \-]{2,40}$/, 'Vennligst oppgi nasjonalitet!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#firstname' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ\.\' \-]{2,30}$/, 'Ugyldig fornavn!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#lastname' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ\.\' \-]{2,30}$/, 'Ugyldig etternavn!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#address' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ0-9\.\' \-]{2,45}$/, 'Ugyldig adresse!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#postal_code' ).on( 'change', function()
	{
		validation( this, /^[0-9]{4,5}$/, 'Ugyldig postnummer!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#city' ).on( 'change', function()
	{
		validation( this, /^[a-zA-ZæøåÆØÅ\.\' \-]{2,40}$/, 'Ugyldig poststed!' );
	});

// *****************************************************
/**
 *
 */	
	$( '#phone' ).on( 'change', function()
	{
		validation( this, /^[0-9]{2,20}$/, 'Ugyldig telefonnummer!' );
	});

// *****************************************************

});
