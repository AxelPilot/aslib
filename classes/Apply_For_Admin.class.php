<?php

// ************************************************************************
/**
 *
 */
class Apply_For_Admin extends Handler
{
	
// ************************************************************************

	protected $notification_ID;
	protected $page_subtitle;

// ************************************************************************
/**
 *
 */
	public function __construct()
	{
		if( $_SESSION[ 'admin' ] == 'N' )
		{
			parent::__construct();
		}
		elseif( $_SESSION[ 'admin' ] == 'applied' )
		{
			redirect( 'index.php?msg=Du har allerede søkt om å bli administrator.' );
		}
		elseif( $_SESSION[ 'admin' ] == 'Y' )
		{
			redirect( 'index.php?msg=Du er allerede administrator.' );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function initial_action()
	{
		$this->set_page_subtitle( 'Administrator-forespørsel' );
		$this->print_page_subtitle();
		include( './includes/apply_for_admin_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function submitted_action()
	{
		$this->set_page_subtitle( 'Administrator-forespørsel' );
		$this->print_page_subtitle();

		try
		{
			$user = new User( $_SESSION[ 'user_ID' ] );
			if( !$user->is_admin() )
			{
				if( !$user->applied_for_admin() )
				{
					if( $user->apply_for_admin() )
					{
						$_SESSION[ 'admin' ] = 'applied';
						redirect( 'index.php?msg=Din søknad om å bli administrator er sendt.<br />'
							. 'Du får beskjed pr e-post når søknaden er behandlet.' );
					}
					else
					{
						redirect( 'index.php?msg=Administrator-søknaden kunne ikke sendes pga teknisk feil.<br />vennligst prøv igjen senere.' );
					}
				}
				else
				{
					redirect( 'index.php?msg=Du har allerede søkt om å bli administrator.' );
				}
			}
			else
			{
				redirect( 'index.php?msg=Du er registrert som administrator fra før.' );
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

		include( './includes/apply_for_admin_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function confirmed_action()
	{
	}

// ************************************************************************

} // End of class Apply_For_Admin.

// ************************************************************************

?>
