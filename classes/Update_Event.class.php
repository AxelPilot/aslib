<?php

// ************************************************************************
/**
 * Note: No checks are made to keep several users from changing the same 
 * event simultaneously.
 */
class Update_Event extends Handler
{
	
// ************************************************************************

	protected $event;
	protected $page_subtitle;
	protected $validation_exceptions;

// ************************************************************************
/**
 *
 */
	protected function initial_action()
	{
		$this->set_page_subtitle( 'Oppdater øvelse' );
		$this->print_page_subtitle();
		include( './includes/update_event_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function submitted_action()
	{
		try
		{
			$this->event = new Event( $_POST[ 'event_ID' ] );

			if( $this->event->exists_in_db() )
			{
				$this->event->set_time( $_POST[ 'event_time' ] );
				$this->event->set_name( $_POST[ 'event_name' ] );
				$this->event->validate_update_data();
				
				// If any changes have been made to the event.
				if( $this->event->is_changed() )
				{
					$this->set_page_subtitle( 'Bekreft endring av øvelse' );
					$this->print_page_subtitle();

					// Show the event confirmation page.
					include( './includes/confirm_event_update_form.inc.php' );
				}
				// If no changes were made.
				else
				{
					$this->set_page_subtitle( 'Oppdater øvelse' );
					$this->print_page_subtitle();

					echo '<p><div class="Error">Du har ikke gjort noen endringer.</div></p>';

					// Show the event registration page again if no changes were made.
					include( './includes/update_event_form.inc.php' );
				}
			}
			else
			{
				$this->set_page_subtitle( 'Oppdater øvelse' );
				$this->print_page_subtitle();

				echo '<p><div class="Error">Øvelsen finnes ikke.</div></p>';

				// Show the event registration page again if the selected event doesn't exist.
				include( './includes/update_event_form.inc.php' );
			}
		}
		catch( AsDbErrorException $e )
		{
			$this->set_page_subtitle( 'Oppdater øvelse' );
			$this->print_page_subtitle();

			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';

			// Show the event registration page again in case of any incorrect form data.
			include( './includes/update_event_form.inc.php' );
		}
		catch( AsDbException $e )
		{
			$this->set_page_subtitle( 'Oppdater øvelse' );
			$this->print_page_subtitle();

			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen.</div></p>';

			// Show the event registration page again in case of any incorrect form data.
			include( './includes/update_event_form.inc.php' );
		}	
		catch( AsFormValidationException $e )
		{
			$this->set_page_subtitle( 'Oppdater øvelse' );
			$this->print_page_subtitle();

			$this->validation_exceptions = $e->getAsMessage();

			// Show the event registration page again in case of any incorrect form data.
			include( './includes/update_event_form.inc.php' );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function confirmed_action()
	{
		$this->set_page_subtitle( 'Oppdater øvelse' );
		$this->print_page_subtitle();

		// Save the event to the database.
		try
		{
			$this->event = new Event( $_POST[ 'event_ID' ] );

			if( $this->event->exists_in_db() )
			{
				$this->event->set_time( $_POST[ 'event_time' ] );
				$this->event->set_name( $_POST[ 'event_name' ] );
				$this->event->validate_update_data();
				if( $this->event->is_changed() )
				{
					$this->event->save_update_to_db();

					// If successful, redirect to index.php and display a confirmation message.
					redirect( 'index.php?msg=Øvelsen er oppdatert med dine endringer.<br />Berørte personer er informert pr e-post.' );
					exit(); // Quit the script.
				}
				else
				{
					// Redirect to index.php and display a message in the event that no changes were made.
					redirect( 'index.php?msg=Du har ikke utført noen endringer.' );
					exit(); // Quit the script.
				}
			}
			else
			{
				redirect( 'update_event.php' );
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
	
		include( './includes/update_event_form.inc.php' );
	}

// ************************************************************************

} // End of class Register_Event.

// ************************************************************************

?>
