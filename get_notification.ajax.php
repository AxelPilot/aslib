<?php
require_once './includes/ajax_header.inc.php';

if( is_loggedIn( true ) )
{
	$result = array();
	if( isset( $_POST[ 'notification_ID' ] ) )
	{
		$notification_ID = ( int ) trim( $_POST[ 'notification_ID' ] );
		if( $notification_ID >= 0 )
		{
			try
			{
				$notification = new Notification( $notification_ID );
				$url = $notification->get_url();
				$params = $notification->get_params();

				$url .= ( $url != "" && $url != "#" ? "?" . ( isset( $params ) ? $params . "&" : "" ) 
					. "nid=" . $notification->get_notification_ID() : "" );

				$result[ 'title' ] = $notification->get_title();
				$result[ 'message' ] = nl2br( $notification->get_message() );
				$result[ 'url' ] = $url;
				$result[ 'registration_date' ] = $notification->get_registration_date();

				$notification->mark_as_read( $_SESSION[ 'user_ID' ], AsException::THROW_NONE );
			}
			catch( AsDbErrorException $e )
			{
				$result[ 'exception' ] = $e->getAsMessage();
			}
			catch( AsDbException $e )
			{
				$result[ 'exception' ] = $e->getAsMessage();
			}	
		}
		echo json_encode( $result );
	}
}
?>