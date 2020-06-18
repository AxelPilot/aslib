<?php
include( './includes/head.inc.php' );
include( './includes/header.inc.php' );

if( is_loggedIn() )
{
	if( isset( $_GET[ 'x' ] ) && isset( $_GET[ 'y' ] ) )
	{
		try
		{
			$user_ID = $_GET[ 'y' ];
			$notification = new Notification( $_GET[ 'x' ], NULL, NULL, NULL, NULL, NULL, AsException::THROW_NO_DB );
			
			if( $user_ID == $_SESSION[ 'user_ID' ] )
			{
				if( !$notification->exists_in_db() )
				{
					throw new AsDbException( 'Beskjeden finnes ikke i systemet.' );
				}
				
				$notification->mark_as_read( $user_ID, AsException::THROW_NONE );
		
				$url = $notification->get_url();
				$params = $notification->get_params();
/*		
				if( ( $url != "" ) && ( $url != "#" ) )
				{
					redirect( $url . ( isset( $params ) ? "?" . $params : "" ), true );
					exit();
				}
				else
				{*/
					include( './includes/footer.inc.php' );
					
					$notification_script = "notification_open_and_mark_as_read('" 
						. $notification->get_notification_ID() . "')";
/*
					$notification_script = "display_notification_in_lightbox('" 
						. $notification->get_notification_ID() . "')";
*/
					redirect( 'index.php', false, $notification_script );
//				}
			}
			elseif( !isset( $_GET[ 'loggedin' ] ) )
			{
				new Logout();
				redirect( "open_notification.php?x=" . $_GET[ 'x' ] . "&y=" . $_GET[ 'y' ] );
				exit();
			}
		}
		catch( AsDbErrorException $e )
		{
			redirect( 'index.php?error=' . $e->getAsMessage() . "<br />Vennligst prøv igjen senere." );
		}
		catch( AsDbException $e )
		{
			redirect( 'index.php?error=' . $e->getAsMessage() );
		}
	}
}

include( './includes/footer.inc.php' );
?>
