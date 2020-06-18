<?php

// ************************************************************************
/**
 *
 */
class Register_User extends Handler
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
		$this->set_page_subtitle( 'Opprett brukerkonto' );
		$this->print_page_subtitle();
		include( './includes/register_user_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function submitted_action()
	{
		$this->set_page_subtitle( 'Opprett brukerkonto' );
		$this->print_page_subtitle();

		// Save the user to the database.
		try
		{
			$user = new User( NULL, $_POST[ 'email' ], $_POST[ 'password1' ], $_POST[ 'password2' ], $_POST[ 'lastname' ], 
				$_POST[ 'firstname' ], $_POST[ 'address' ], $_POST[ 'postal_code' ], $_POST[ 'city' ], $_POST[ 'phone' ] );
				
			$user->save_to_db();

			$title = 'Velkommen!';
			$message = 'Takk for din registrering!' . "\r\n";
			$message .= 'Velkommen som bruker av Liksom-Ski-VM.' . "\r\n";
			$notification = new Notification( NULL, $user, $title, $message );

			if( $_POST[ 'admin' ] == 'apply_for_admin' )
			{
				$user->apply_for_admin();
			}

			// If successful, redirect to index.php and display a confirmation message.
			redirect( 'index.php?msg=Takk for din registrering! En bekreftelse har blitt sendt til e-postadressen din.<br />Vennligst klikk på lenken i e-posten for å aktivere kontoen din.' );
			exit(); // Quit the script.
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
	
		include( './includes/register_user_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function confirmed_action()
	{
	}

// ************************************************************************

} // End of class Register_User.

// ************************************************************************

?>
