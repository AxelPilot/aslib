<?php
require_once './includes/ajax_header.inc.php';

if( is_loggedIn( true ) )
{
	$result = array();
	if( isset( $_GET[ 'event_ID' ] ) )
	{
		$event_ID = ( int ) trim( $_GET[ 'event_ID' ] );
		if( $event_ID >= 0 )
		{
			try
			{
				$event = new Event( $event_ID );
				$result[ 'event_time' ] = date( 'Y-m-d H:i', strtotime( $event->get_time() ) );
				$result[ 'event_name' ] = $event->get_name();
			}
			catch( AsDbErrorException $e )
			{
			}
			catch( AsDbException $e )
			{
			}	
			catch( AsFormValidationException $e )
			{
			}
		}
		echo json_encode( $result );
	}
}
?>