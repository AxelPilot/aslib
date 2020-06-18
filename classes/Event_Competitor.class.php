<?php

// ************************************************************************

class Event_Competitor extends Entity
{

// ************************************************************************

	protected $competitor; // The competitor.
	protected $event;	   // The event.
	protected $timestamp; // The time the registration was made.

// ************************************************************************

	public function __construct( $competitor, $event_ID, $timestamp, $throw_exceptions = AsException::THROW_ALL )
	{
		$this->competitor = $competitor;
		$this->event = new Event( $event_ID, NULL, NULL, AsException::THROW_NO_VALIDATION );
		$this->timestamp = $timestamp;
		
		$this->validate_data();

		if( $this->exists_in_db() && ( $throw_exceptions >= AsException::THROW_DB ) )
		{
			throw new AsDbException( 'Vedkommende er allerede registrert som deltager til denne øvelsen.' );
		}
	}

// ************************************************************************

	public function save_to_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( $this->exists_in_db() )
		{
			if( $throw_exceptions >= AsException::THROW_DB )
			{
				throw new AsDbException( 'Vedkommende er allerede registrert som deltager til denne øvelsen.' );
			}
			return false;
		}
		else
		{
			$this->competitor->save_competitor_to_db( $throw_exceptions );

			if ( $mysqli = AsMySQLi::connect2db( 'Beklager, men registreringen kunne ikke fullføres på grunn av teknisk feil.' ) )
			{
				// Add the event_competitor to the database.
				$query = "
				INSERT INTO " . TABLE_PREFIX . "event_competitor (user_ID, event_ID, time)
				VALUES ('" . $this->competitor->get_user_ID() . "',
				'" . $this->event->get_event_ID() . "',
				'" . date( "Y-m-d H:i:s", $this->timestamp ) . "')";
				
				$result = $mysqli->query( $query );
				
				// If the data were successfully inserted into the database...
				if ( $mysqli->affected_rows == 1 )
				{
					$mysqli->close();
					return true;
				}
				else // If query was unsuccessful.
				{
					$mysqli->close();
					if( $throw_exceptions >= AsException::THROW_DB_ERROR )
					{
						throw new AsDbErrorException( 'Beklager, men registreringen kunne ikke fullføres på grunn av teknisk feil.' );
					}
					return false;
				}
			}
		}
	} // End of public function save_to_db().
	
// ************************************************************************

	public function send_confirmation_email()
	{
		// Compose the confirmation email.
		$subject = 'Bekreftelse';
		
		$message = "Kjære " . $this->competitor->get_firstname() . " " . $this->competitor->get_lastname() . "!\r\n\r\n";
		$message .= "Du er registrert for deltagelse i flg. øvelse:\r\n\r\n";
		$message .= $this->event->get_name() . "\r\n";
		$message .= date( "d.m.Y \k\l H:i", strtotime( $this->event->get_time() ) ) . "\r\n\r\n";
		$message .= 'Bestilling utført: ' . date( "d.m.Y \k\l H:i", $this->timestamp ) . "\r\n";

		$email = new Email( $this->competitor->get_email(), $subject, $message );
		return $email->send();
	} // public function send_confirmation_email().

// ************************************************************************
/**
 * Checks if the event_competitor exists in the database.
 *
 * Returns an array containing the event_competitor data if the 
 * event_competitor exists in the database.
 * Returns false if the event_competitor doesn't exist in the database.
 */
	public function exists_in_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( $mysqli = AsMySQLi::connect2db( $technical_error ) )
		{
			$query = "";
		
			// Query to check if an identical event_competitor is already registered
			// in the database.
			if( $this->competitor->get_user_ID() && $this->event->get_event_ID() )
			{
				$query = "
				SELECT user_ID, event_ID
				FROM " . TABLE_PREFIX . "event_competitor
				WHERE
				user_ID = " . $this->competitor->get_user_ID() . "
				AND
				event_ID = " . $this->event->get_event_ID();
			}
			else
			{
				$mysqli->close();
				return false;
			}

			if( $result = $mysqli->query( $query ) )
			{
				// If the event_competitor is previously registered.
				if ( $result->num_rows > 0 )
				{
					$row = $result->fetch_assoc();
					$event_competitor = $row;
				
					$mysqli->close();
					return $event_competitor; 
				}
				else // Did NOT find the event_competitor in the database.
				{
					$mysqli->close();
					return false; 
				}
			}
			else // If query unsuccessful.
			{
				$mysqli->close();
				if( $throw_exceptions >= AsException::THROW_DB_ERROR )
				{
					throw new AsDbErrorException( $technical_error );
				}
				return false;
			}
		}
	} // End of function exists_in_db.

// ************************************************************************
/**
 *
 */
	public function validate_data( $throw_exceptions = AsException::THROW_ALL )
	{
		$e = array_merge( $this->event->get_validation_messages(), $this->competitor->get_validation_messages() );

		if( count( $e ) > 0 )
		{
			if( $throw_exceptions >= AsException::THROW_VALIDATION )
			{
				throw new AsFormValidationException( $e );
			}
			return false;
		}
		else
		{
			return true;
		}
	}

// ************************************************************************
/**
 * Customer get function.
 */

	public function get_competitor()
	{
		return $this->competitor;
	}

// ************************************************************************
/**
 * Ticket_type get function.
 */
	public function get_event()
	{
		return $this->event;
	}

// ************************************************************************
/**
 * Timestamp get function.
 */
	public function get_timestamp()
	{
		return $this->timestamp;
	}

// ************************************************************************

} // end of class Event_Competitor.

// ************************************************************************

?>
