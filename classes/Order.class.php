<?php

// ************************************************************************

class Order extends Entity
{

// ************************************************************************

	protected $order_ID; // The order ID.
	protected $customer; // The customer who made the order.
	protected $event; // Event for the order.
	protected $ticket_count; // Number of tickets ordered.
	protected $timestamp; // The time the order was made.

// ************************************************************************

	public function __construct( $customer, $event_ID, $ticket_count, $timestamp )
	{
		$this->customer = $customer;
		$this->event = new Event( $event_ID, NULL, NULL, AsException::THROW_NO_VALIDATION );
		$this->ticket_count = $ticket_count;
		$this->timestamp = $timestamp;
		
		$this->validate_data();
	}

// ************************************************************************

	public function save_to_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if ( $mysqli = AsMySQLi::connect2db( 'Beklager, men bestillingen kunne ikke registreres på grunn av teknisk feil.' ) )
		{
			// Add the order to the database.
			$query = "
			INSERT INTO " . TABLE_PREFIX . "order (user_ID, event_ID, ticket_count, time)
			VALUES ('" . $this->customer->get_user_ID() . "',
			'" . $this->event->get_event_ID() . "',
			'" . $this->ticket_count . "',
			'" . date( "Y-m-d H:i:s", $this->timestamp ) . "')";
				
			$result = $mysqli->query( $query );
				
			// If the data were successfully inserted into the database...
			if ( $mysqli->affected_rows == 1 )
			{
				// Retrieves the auto generated order_ID from the database.
				$this->order_ID = $mysqli->insert_id;

				$mysqli->close();
				return $this->order_ID;
			}
			else // If query was unsuccessful.
			{
				$mysqli->close();
				if( $throw_exceptions >= AsException::THROW_DB_ERROR )
				{
					throw new AsDbErrorException( 'Beklager, men bestillingen kunne ikke registreres på grunn av teknisk feil.' );
				}
				return false;
			}
		}
	} // End of public function save_to_db().
	
// ************************************************************************
/**
 *
 */
	public function send_confirmation_email()
	{
		// Compose the confirmation email.
		$subject = 'Bestillingsbekreftelse';
		
		$message = "Kjaere " . $this->customer->get_firstname() . " " . $this->customer->get_lastname() . "!\r\n\r\n";
		$message .= "Takk for din bestilling med folgende informasjon:\r\n\r\n";
		$message .= "Arrangement: " . $this->event->get_name() . ", ";
		$message .= date( "d.m.Y \k\l H:i", strtotime( $this->event->get_time() ) ) . " \r\n\r\n";
		$message .= "Antall billetter: " . $this->ticket_count . "\r\n\r\n";
		$message .= "Bestilt: " . date( "d.m.Y \k\l H:i", $this->timestamp ) . "\r\n";

		$email = new Email( $this->customer->get_email(), $subject, $message );
		return $email->send();
	} // public function send_confirmation_email().

// ************************************************************************
/**
 *
 */
	public function validate_data( $throw_exceptions = AsException::THROW_ALL )
	{
		$e = array_merge( $this->event->get_validation_messages(), $this->customer->get_validation_messages() );

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
	public function get_customer()
	{
		return $this->customer;
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
 * Ticket_count get function.
 */
	public function get_ticket_count()
	{
		return $this->ticket_count;
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

} // end of class Order.

// ************************************************************************

?>
