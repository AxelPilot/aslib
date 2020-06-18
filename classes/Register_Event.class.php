<?php

// ************************************************************************
/**
 *
 */
class Register_Event extends Handler
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
		$this->set_page_subtitle( 'Registrer øvelse' );
		$this->print_page_subtitle();
		include( './includes/register_event_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function submitted_action()
	{
		try
		{
			$this->event = new Event( NULL, $_POST[ 'event_time' ], $_POST[ 'event_name' ] );
			$this->set_page_subtitle( 'Bekreft registrering av øvelse' );
			$this->print_page_subtitle();
			echo $this->event->exists_in_db() ? 
			'<div class="Caution">En øvelse av samme type er allerede registrert på samme tidspunkt.</div>
			<div class="Error">Ønsker du å registrere den nye øvelsen likevel?</div>' : '';

			// Show the event confirmation page.
			include( './includes/confirm_event_form.inc.php' );
		}
		catch( AsDbErrorException $e )
		{
			$this->set_page_subtitle( 'Registrer øvelse' );
			$this->print_page_subtitle();

			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';

			include( './includes/register_event_form.inc.php' );
		}
		catch( AsDbException $e )
		{
			$this->set_page_subtitle( 'Registrer øvelse' );
			$this->print_page_subtitle();

			echo '<p><div class="Error">' . $e->getAsMessage() . '</div></p>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';

			include( './includes/register_event_form.inc.php' );
		}	
		catch( AsFormValidationException $e )
		{
			$this->set_page_subtitle( 'Registrer øvelse' );
			$this->print_page_subtitle();

			$this->validation_exceptions = $e->getAsMessage();

			// Show the event registration page again in case of any incorrect form data.
			include( './includes/register_event_form.inc.php' );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function confirmed_action()
	{
		$this->set_page_subtitle( 'Registrer øvelse' );
		$this->print_page_subtitle();

		// Save the event to the database.
		try
		{
			$event = new Event( NULL, $_POST[ 'event_time' ], $_POST[ 'event_name' ] );
			$event->save_to_db( Event::ALLOW_DUPLICATES );

			// If successful, redirect to index.php and display a confirmation message.
			redirect( 'index.php?msg=Øvelsen er registrert.' );
			exit(); // Quit the script.
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
	
		include( './includes/register_event_form.inc.php' );
	}

// ************************************************************************

} // End of class Register_Event.

// ************************************************************************

?>
