<?php

// ************************************************************************
/**
 *
 */
class Delete_Event extends Handler
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
		$this->set_page_subtitle( 'Slett øvelse' );
		$this->print_page_subtitle();
		include( './includes/delete_event_form.inc.php' );
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
				$this->set_page_subtitle( 'Bekreft sletting av øvelse' );
				$this->print_page_subtitle();

				// Show the event deletion confirmation page.
				include( './includes/confirm_event_deletion_form.inc.php' );
			}
			else
			{
				redirect( 'index.php?msg=Øvelsen er allerede slettet.' );
			}
		}
		catch( AsDbErrorException $e )
		{
			$this->set_page_subtitle( 'Slett øvelse' );
			$this->print_page_subtitle();

			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';

			include( './includes/delete_event_form.inc.php' );
		}
		catch( AsDbException $e )
		{
			$this->set_page_subtitle( 'Slett øvelse' );
			$this->print_page_subtitle();

			echo '<p><div class="Error">' . $e->getAsMessage() . '</div></p>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';

			include( './includes/delete_event_form.inc.php' );
		}	
		catch( AsFormValidationException $e )
		{
			$this->set_page_subtitle( 'Slett øvelse' );
			$this->print_page_subtitle();

			$this->validation_exceptions = $e->getAsMessage();

			// Show the event registration page again in case of any incorrect form data.
			include( './includes/delete_event_form.inc.php' );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function confirmed_action()
	{
		$this->set_page_subtitle( 'Slett øvelse' );
		$this->print_page_subtitle();

		// Delete the event from the database.
		try
		{
			$this->event = new Event( $_POST[ 'event_ID' ] );
			if( $this->event->exists_in_db() )
			{
				$this->event->delete_from_db();

				// If successful, redirect to index.php and display a confirmation message.
				redirect( 'index.php?msg=Øvelsen er slettet.<br />Berørte personer er informert pr e-post.' );
				exit(); // Quit the script.
			}
			else
			{
				// If the event doesn't exist, redirect to index.php and display a message.
				redirect( 'index.php?msg=Øvelsen finnes ikke.' );
				exit(); // Quit the script.
			}
		}
		catch( AsDbErrorException $e )
		{
			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';
		}	
		catch( AsDbException $e )
		{
			echo '<p><div class="Error">' . $e->getAsMessage() . '</div></p>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';
		}	
		catch( AsFormValidationException $e )
		{
			$this->validation_exceptions = $e->getAsMessage();
		}	
	
		include( './includes/delete_event_form.inc.php' );
	}

// ************************************************************************

} // End of class Register_Event.

// ************************************************************************

?>
