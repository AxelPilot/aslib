<?php

// ************************************************************************
/**
 *
 */
class Process_Admin_Request extends Handler
{
	
// ************************************************************************

	protected $user;
	protected $admin_activation_code;
	protected $notification_ID;
	protected $page_subtitle;
	protected $approved_status;

// ************************************************************************
/**
 *
 */
	public function __construct()
	{
		if( $_SESSION[ 'admin' ] == 'Y' )
		{
			parent::__construct();
		}
		else
		{
			redirect( 'index.php?msg=Du har ikke rettigheter til å vise den siden<br />fordi du ikke er registrert som administrator.' );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function initial_action()
	{
		$this->approved_status = false;
		if( isset( $_GET[ 'x' ] ) && isset( $_GET[ 'y' ] ) && isset( $_GET[ 'nid' ] ) )
		{
			$this->set_page_subtitle( 'Behandle Administrator-forespørsel' );
			$this->print_page_subtitle();

			try
			{
				$this->user = new User( $_GET[ 'x' ] );
				$this->admin_activation_code = $_GET[ 'y' ];
				$this->notification_ID = $_GET[ 'nid' ];
			}
			catch( AsDbErrorException $e )
			{
				echo '<div class="Error">' . $e->getAsMessage() . '</div>';
				echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';
			}	
			catch( AsDbException $e )
			{
				redirect( 'index.php?error=' . $e->getAsMessage() );
			}	
	
			if( isset( $this->user ) )
			{
				if( !$this->user->is_admin() )
				{
					include( './includes/process_admin_request_form.inc.php' );
				}
				else
				{
					echo '<div class="Message">' . $this->user->get_firstname() . ' ' . $this->user->get_lastname() 
						. ' er allerede administrator.</div>';
				}
			}
		}
		else
		{
			redirect( 'index.php?error=Beklager, men det har oppstått en teknisk feil.<br />Vennligst prøv igjen senere.' );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function submitted_action()
	{
		$this->approved_status = $_POST[ 'approved_status' ];
		$this->set_page_subtitle( 'Behandle Administrator-forespørsel' );
		$this->print_page_subtitle();

		try
		{
			$this->user = new User( $_POST[ 'user_ID' ] );
			if( !$this->user->is_admin() )
			{
				if( $_POST[ 'approved_status' ] )
				{
					$this->user->approve_admin( $_POST[ 'admin_activation_code' ] );
					$notification = new Notification( $_POST[ 'nid' ] );
					$notification->delete_from_db();
					redirect( 'index.php?msg=Du har godkjent søknaden.<br />Brukeren er informert pr e-post.' );
				}
				else
				{
					$this->user->deny_admin( $_POST[ 'admin_activation_code' ] );
					$notification = new Notification( $_POST[ 'nid' ] );
					$notification->delete_from_db();
					redirect( 'index.php?msg=Du har avslått søknaden.<br />Brukeren er informert pr e-post.' );
				}
			}
			else
			{
				redirect( 'index.php?msg=' . $this->user->get_firstname() . ' ' 
					. $this->user->get_lastname() . ' er administrator fra før.' );
			}
		}
		catch( AsDbErrorException $e )
		{
			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';
		}	
		catch( AsDbException $e )
		{
			redirect( 'index.php?error=' . $e->getAsMessage() );
		}	
		catch( AsFormValidationException $e )
		{
			$errors = $e->getAsMessage();
			foreach( $errors as $value )
			{
				echo '<div class="Error">' . $value . '</div>';
			}
			unset( $value );

			echo '<p><div class="Error">Vennligst prøv igjen.</div></p>';
		}	

		$this->approved_status = true;
		include( './includes/process_admin_request_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function confirmed_action()
	{
	}

// ************************************************************************

} // End of class Process_Admin_Request.

// ************************************************************************

?>
