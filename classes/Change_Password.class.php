<?php

// ************************************************************************
/**
 *
 */
class Change_Password extends Handler
{
	
// ************************************************************************

	protected $user;
	protected $page_subtitle;
	protected $validation_exceptions;

// ************************************************************************
/**
 *
 */
	protected function initial_action()
	{
		$this->set_page_subtitle( 'Endre passord' );
		$this->print_page_subtitle();
		include( './includes/change_password_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function submitted_action()
	{
		$this->set_page_subtitle( 'Endre passord' );
		$this->print_page_subtitle();

		try
		{
			$user = new User( $_SESSION[ 'user_ID' ] );
			if( $user->change_password( $_POST[ 'password' ], $_POST[ 'password1' ], $_POST[ 'password2' ] ) )
			{
				// If successful, redirect to index.php and display a confirmation message.
				redirect( 'index.php?msg=Passordet ditt har blitt endret.' );
				exit();
			}
		}
		catch( AsDbErrorException $e )
		{
			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';
		}
		catch( AsDbException $e )
		{
			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen.</div></p>';
		}
		catch( AsFormValidationException $e )
		{
			$this->validation_exceptions = $e->getAsMessage();
		}

		include( './includes/change_password_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function confirmed_action()
	{
	}

// ************************************************************************

} // End of class Change_Password.

// ************************************************************************

?>
