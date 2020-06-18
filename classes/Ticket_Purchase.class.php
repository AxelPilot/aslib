<?php

// ************************************************************************
/**
 *
 */
class Ticket_Purchase extends Handler
{
	
// ************************************************************************

	protected $order;
	protected $page_subtitle;
	protected $validation_exceptions;

// ************************************************************************
/**
 *
 */
	protected function initial_action()
	{
		$this->set_page_subtitle( 'Bestill billetter' );
		$this->print_page_subtitle();
		include( './includes/buy_ticket_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function submitted_action()
	{
		try
		{
			$this->order = new Order( new Customer( $_SESSION[ 'user_ID' ], AsException::THROW_NO_VALIDATION ), 
				$_POST[ 'event_ID' ], $_POST[ 'ticket_count' ], time() );
			
			if( $this->order->get_event()->exists_in_db() )
			{
				$this->set_page_subtitle( 'Bekreft bestilling' );
				$this->print_page_subtitle();

				include( './includes/confirm_order_form.inc.php' );
			}
			else
			{
				$this->set_page_subtitle( 'Bestill billetter' );
				$this->print_page_subtitle();

				echo '<p><div class="Error">Øvelsen finnes ikke!</div></p>';

				include( './includes/buy_ticket_form.inc.php' );
			}
		}
		catch( AsDbErrorException $e )
		{
			$this->set_page_subtitle( 'Bestill billetter' );
			$this->print_page_subtitle();

			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';

			include( './includes/buy_ticket_form.inc.php' );
		}
		catch( AsDbException $e )
		{
			$this->set_page_subtitle( 'Bestill billetter' );
			$this->print_page_subtitle();

			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen.</div></p>';

			include( './includes/buy_ticket_form.inc.php' );
		}	
		catch( AsFormValidationException $e )
		{
			$this->set_page_subtitle( 'Bestill billetter' );
			$this->print_page_subtitle();

			$this->validation_exceptions = $e->getAsMessage();

			// Show the ticket purchase page again in case of any incorrect form data.
			include( './includes/buy_ticket_form.inc.php' );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function confirmed_action()
	{
		$this->set_page_subtitle( 'Bestill billetter' );
		$this->print_page_subtitle();

		// Save the event to the database.
		try
		{
			$this->order = new Order( new Customer( $_SESSION[ 'user_ID' ], AsException::THROW_NO_VALIDATION ), 
				$_POST[ 'event_ID' ], $_POST[ 'ticket_count' ], $_POST[ 'timestamp' ] );
	
			// Store the order in the database and send a confirmation email to the user.
			if( $this->order->get_event()->exists_in_db() )
			{
				$this->order->save_to_db();
				$this->order->send_confirmation_email();

				// If successful, redirect to index.php and display a confirmation message.
				redirect( 'index.php?msg=Bestillingen er registrert. En bekreftelse er sendt deg pr e-post.' );
				exit(); // Quit the script.
			}
			else
			{
				// If successful, redirect to index.php and display a confirmation message.
				redirect( 'index.php?msg=Øvelsen som du prøver å bestille billetter til finnes ikke.' );
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
			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen.</div></p>';
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
	
		include( './includes/buy_ticket_form.inc.php' );
	}

// ************************************************************************

} // End of class Event_registration.

// ************************************************************************

?>
