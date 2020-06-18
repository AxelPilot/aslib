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

});
