<?php

// ************************************************************************
/**
 *
 */
class Register_Competitor extends Handler
{
	
// ************************************************************************

	protected $event_competitor;
	protected $competitor;
	protected $page_subtitle;

	protected $nationality;
	protected $lastname;
	protected $firstname;
	protected $address;
	protected $postal_code;
	protected $city;
	protected $phone;

	protected $validation_exceptions;

// ************************************************************************
/**
 *
 */
	protected function initial_action()
	{
		$this->set_page_subtitle( 'Registrer utøver' );
		$this->print_page_subtitle();
		include( './includes/register_competitor_form.inc.php' );
	}

// ************************************************************************
/**
 *
 */
	protected function submitted_action()
	{
		try
		{
			$this->competitor = new Competitor( NULL, $_POST[ 'email' ], 
												isset( $_POST[ 'nationality' ] ) ? $_POST[ 'nationality' ] : NULL, 
												isset( $_POST[ 'lastname' ] ) ? $_POST[ 'lastname' ] : NULL, 
												isset( $_POST[ 'firstname' ] ) ? $_POST[ 'firstname' ] : NULL, 
												isset( $_POST[ 'address' ] ) ? $_POST[ 'address' ] : NULL, 
												isset( $_POST[ 'postal_code' ] ) ? $_POST[ 'postal_code' ] : NULL, 
												isset( $_POST[ 'city' ] ) ? $_POST[ 'city' ] : NULL, 
												isset( $_POST[ 'phone' ] ) ? $_POST[ 'phone' ] : NULL, 
												AsException::THROW_NO_VALIDATION );
			
			if( $this->competitor->exists_in_db() )
			{
				$this->nationality = $this->competitor->get_nationality();
				$this->lastname = $this->competitor->get_lastname();
				$this->firstname = $this->competitor->get_firstname();
				$this->address = $this->competitor->get_address();
				$this->postal_code = $this->competitor->get_postal_code();
				$this->city = $this->competitor->get_city();
				$this->phone = $this->competitor->get_phone();
			}
			else
			{
				$this->nationality = isset( $_POST[ 'nationality' ] ) ? $_POST[ 'nationality' ] : NULL;
				$this->lastname = isset( $_POST[ 'lastname' ] ) ? $_POST[ 'lastname' ] : NULL;
				$this->firstname = isset( $_POST[ 'firstname' ] ) ? $_POST[ 'firstname' ] : NULL;
				$this->address = isset( $_POST[ 'address' ] ) ? $_POST[ 'address' ] : NULL;
				$this->postal_code = isset( $_POST[ 'postal_code' ] ) ? $_POST[ 'postal_code' ] : NULL;
				$this->city = isset( $_POST[ 'city' ] ) ? $_POST[ 'city' ] : NULL;
				$this->phone = isset( $_POST[ 'phone' ] ) ? $_POST[ 'phone' ] : NULL;
			}

			$this->event_competitor = new Event_Competitor( $this->competitor, $_POST[ 'event_ID' ], time() );
			
			$this->set_page_subtitle( 'Bekreft utøver' );
			$this->print_page_subtitle();

			include( './includes/confirm_competitor_form.inc.php' );
		}
		catch( AsDbErrorException $e )
		{
			$this->set_page_subtitle( 'Registrer utøver' );
			$this->print_page_subtitle();

			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';

			include( './includes/register_competitor_form.inc.php' );
		}
		catch( AsDbException $e )
		{
			$this->set_page_subtitle( 'Registrer utøver' );
			$this->print_page_subtitle();

			echo '<p><div class="Error">' . $e->getAsMessage() . '</div></p>';

			include( './includes/register_competitor_form.inc.php' );
		}	
		catch( AsFormValidationException $e )
		{
			$this->set_page_subtitle( 'Registrer utøver' );
			$this->print_page_subtitle();

			$this->validation_exceptions = $e->getAsMessage();

			// Show the ticket purchase page again in case of any incorrect form data.
			include( './includes/register_competitor_form.inc.php' );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function confirmed_action()
	{
		$this->set_page_subtitle( 'Registrer utøver' );
		$this->print_page_subtitle();

		// Save the event to the database.
		try
		{
			$this->competitor = new Competitor( NULL, $_POST[ 'email' ], $_POST[ 'nationality' ], $_POST[ 'lastname' ], $_POST[ 'firstname' ], 
				$_POST[ 'address' ], $_POST[ 'postal_code' ], $_POST[ 'city' ], $_POST[ 'phone' ], AsException::THROW_NO_VALIDATION );

			$this->event_competitor = new Event_Competitor( $this->competitor, $_POST[ 'event_ID' ], time() );
	
			// Store order in database and send a confirmation email to the user.
			$this->event_competitor->save_to_db();
			$this->event_competitor->send_confirmation_email();

			// If successful, redirect to index.php and display a confirmation message.
			redirect( 'index.php?msg=' . $this->competitor->get_firstname() . ' ' . $this->competitor->get_lastname() . ' er registrert for deltagelse i ' . $this->event_competitor->get_event()->get_name() . '.<br />En bekreftelse er sendt vedkommende pr e-post.' );
			exit();
		}
		catch( AsDbErrorException $e )
		{
			echo '<div class="Error">' . $e->getAsMessage() . '</div>';
			echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';
		}	
		catch( AsDbException $e )
		{
			echo '<p><div class="Error">' . $e->getAsMessage() . '</div></p>';
		}	
		catch( AsFormValidationException $e )
		{
			$this->validation_exceptions = $e->getAsMessage();
		}	
	
		include( './includes/register_competitor_form.inc.php' );
	}

// ************************************************************************

} // End of class Register_Competitor.

// ************************************************************************

?>
