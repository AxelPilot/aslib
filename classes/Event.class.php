<?php

// ************************************************************************
/**
 *
 */
class Event extends Entity
{
	
// ************************************************************************

	const ALLOW_DUPLICATES = true; // Allow duplicates to be stored in the database.
	const NO_DUPLICATES = false; // Allow no duplicates to be stored in the database.
	
	protected $event_ID; // The event ID.
	protected $time; 	 // Time of the event.
	protected $name; 	 // Name of the event.
	
	protected $validation_messages = array();

// ************************************************************************
/**
 *
 */
	public function __construct( $event_ID, $time = NULL, $name = NULL, $throw_exceptions = AsException::THROW_ALL )
	{
		if( $event_ID )
		{
			$v = $this->validate_data( $event_ID, NULL, NULL, $throw_exceptions );
			if( $v && !is_array( $v ) )
			{
				$this->event_ID = $event_ID;
				if( !$this->retrieve_from_db( $throw_exceptions ) )
				{
					if( $throw_exceptions >= AsException::THROW_DB )
					{
						throw new AsDbException( 'Øvelsen finnes ikke.' );
					}
				}
			}
			elseif( is_array( $v ) && ( $throw_exceptions < AsException::THROW_VALIDATION ) )
			{
				$this->validation_messages = array_merge( $this->validation_messages, $v );
			}
		}
		else
		{
			$v = $this->validate_data( NULL, $time, $name, $throw_exceptions );
			if( $v && !is_array( $v ) )
			{
				$this->time = trim( $time );
				$this->name = trim( $name );
				$this->retrieve_from_db( $throw_exceptions );
			}
			elseif( is_array( $v ) && ( $throw_exceptions < AsException::THROW_VALIDATION ) )
			{
				$this->validation_messages = array_merge( $this->validation_messages, $v );
			}
		}
	}

// ************************************************************************
/**
 * Saves the event to the database.
 *
 * Returns the auto generated event ID upon success.
 * Returns false upon failure.
 */
	public function save_to_db( $create_duplicate = self::NO_DUPLICATES, $throw_exceptions = AsException::THROW_ALL )
	{
		if( $this->exists_in_db() && ( $create_duplicate == self::NO_DUPLICATES ) )
		{
			if( $throw_exceptions >= AsException::THROW_DB )
			{
				throw new AsDbException( 'En identisk øvelse er allerede registrert på samme tidspunkt.' );
			}
			return false;
		}
		elseif ( $mysqli = AsMySQLi::connect2db( 'Beklager, men øvelsen kunne ikke registreres på grunn av teknisk feil.' ) )
		{
			// Add the event to the database.
			$query = "
			INSERT INTO " . TABLE_PREFIX . "event (time, name)
			VALUES ('" . $this->time . "', '" . $mysqli->escape_data( $this->name ) . "')";
				
			$result = $mysqli->query( $query );
				
			// If the data were successfully inserted into the database...
			if ( $mysqli->affected_rows == 1 )
			{
				// Retrieves the auto generated event_ID from the database.
				$this->event_ID = $mysqli->insert_id;

				$mysqli->close();

				// Returns the event_ID.
				return $this->event_ID;
			}
			else // If query was unsuccessful.
			{
				$mysqli->close();

				if( $throw_exceptions >= AsException::THROW_DB_ERROR )
				{
					throw new AsDbErrorException( 'Beklager, men øvelsen kunne ikke registreres på grunn av teknisk feil.' );
				}
				return false;
			}
		}
	} // End of function save_to_db().

// ************************************************************************
/**
 * Saves the event to the database.
 *
 * Returns the auto generated event ID upon success.
 * Returns false upon failure.
 */
	public function save_update_to_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( ( $r = $this->exists_in_db( $throw_exceptions ) ) && is_array( $r ) && ( $mysqli = AsMySQLi::connect2db( 'Beklager, men øvelsen kunne ikke oppdateres på grunn av teknisk feil.' ) ) )
		{
			$time_is_changed = ( strtotime( trim( $r[ 'time' ] ) ) != strtotime( trim( $this->get_time() ) ) );
			$name_is_changed = ( strcasecmp( trim( $r[ 'name' ] ), trim( $this->get_name() ) ) != 0 );

			if( $time_is_changed || $name_is_changed )
			{
				$customers = $this->get_customers();
				$competitors = $this->get_competitors();
				$oldname = $this->get_name_from_db();
				$oldtime = $this->get_time_from_db();
			
				// Update the event in the database.
				$query = "
				UPDATE " . TABLE_PREFIX . "event";
				
				$query .= "
				SET ";
				$query .= $time_is_changed ? "time = '" . $this->get_time() . "'" : "";
				$query .= $time_is_changed && $name_is_changed ? ", " : "";
				$query .= $name_is_changed ? "name = '" . $this->get_name() . "'" : "";
				
				$query .= "
				WHERE
				event_ID = " . $this->get_event_ID();
				
				$result = $mysqli->query( $query );
				
				// If the data were successfully inserted into the database...
				if ( $mysqli->affected_rows == 1 )
				{
					$mysqli->close();
//					new Notification( NULL, $customers, 'Endring av øvelse', 
//						'Øvelsen ' . $oldname . ' ' . $oldtime . ' som du har kjøpt billetter til er blitt endret.', '#' );
					$this->send_update_notification_emails( $customers, $competitors, $oldname, $oldtime );

					// Returns the event_ID.
					return $this->event_ID;
				}
				else // If query was unsuccessful.
				{
					$mysqli->close();

					if( $throw_exceptions >= AsException::THROW_DB_ERROR )
					{
						throw new AsDbErrorException( 'Beklager, men øvelsen kunne ikke oppdateres på grunn av teknisk feil.' );
					}
					return false;
				}
			}
			else
			{
				$mysqli->close();
				return false;
			}
		}
	} // End of function save_update_to_db().

// ************************************************************************
/**
 *
 */
	protected function send_update_notification_emails( $customers, $competitors, $oldname, $oldtime )
	{
		$ok = true;
		$subject = 'Endring av arrangement';

		if( is_array( $customers ) )
		{
			foreach( $customers as $row )
			{
				$body = "Kjaere " . $row[ 'firstname' ] . " " . $row[ 'lastname' ] . "!\r\n\r\n";
				$body .= $oldname . ", ";
				$body .= $oldtime . " som du har kjoept ". $row[ 'tickets' ] ." billetter til har blitt endret.\r\n\r\n";
				$body .= "Oppdatert informasjon:\r\n\r\n";
				$body .= "Arrangement: " . $this->get_name() . ", ";
				$body .= date( "d.m.Y \k\l H:i", strtotime( $this->get_time() ) ) . " \r\n";
			
				$email = new Email( $row[ 'email' ], $subject, $body );
				if( !$email->send() )
				{
					$ok = false;
				}
			}
		}

		if( is_array( $competitors ) )
		{
			foreach( $competitors as $row )
			{
				$body = "Kjaere " . $row[ 'firstname' ] . " " . $row[ 'lastname' ] . "!\r\n\r\n";
				$body .= $oldname . ", ";
				$body .= $oldtime . " hvor du er registrert som deltaker har blitt endret.\r\n\r\n";
				$body .= "Oppdatert informasjon:\r\n\r\n";
				$body .= "Arrangement: " . $this->get_name() . ", ";
				$body .= date( "d.m.Y \k\l H:i", strtotime( $this->get_time() ) ) . " \r\n";
			
				$email = new Email( $row[ 'email' ], $subject, $body );
				if( !$email->send() )
				{
					$ok = false;
				}
			}
		}
		return $ok;
	}

// ************************************************************************
/**
 *
 */
	protected function send_cancellation_emails( $customers, $competitors, $oldname, $oldtime )
	{
		$ok = true;
		$subject = 'Kansellert arrangement';

		if( is_array( $customers ) )
		{
			foreach( $customers as $row )
			{
				$body = "Kjaere " . $row[ 'firstname' ] . " " . $row[ 'lastname' ] . "!\r\n\r\n";
				$body .= $oldname . ", ";
				$body .= $oldtime . "som du har kjoept ". $row[ 'tickets' ] ." billetter til har blitt kansellert.\r\n";
			
				$email = new Email( $row[ 'email' ], $subject, $body );
				if( !$email->send() )
				{
					$ok = false;
				}
			}
		}

		if( is_array( $competitors ) )
		{
			foreach( $competitors as $row )
			{
				$body = "Kjaere " . $row[ 'firstname' ] . " " . $row[ 'lastname' ] . "!\r\n\r\n";
				$body .= $oldname . ", ";
				$body .= $oldtime . "hvor du er registrert som deltaker har blitt kansellert.\r\n";
			
				$email = new Email( $row[ 'email' ], $subject, $body );
				if( !$email->send() )
				{
					$ok = false;
				}
			}
		}
		return $ok;
	}

// ************************************************************************
/**
 *
 */
	public function is_changed( $throw_exceptions = AsException::THROW_ALL )
	{
		if( ( $r = $this->exists_in_db( $throw_exceptions ) ) && is_array( $r ) )
		{
			$time_is_changed = ( strtotime( trim( $r[ 'time' ] ) ) != strtotime( trim( $this->get_time() ) ) );
			$name_is_changed = ( strcasecmp( trim( $r[ 'name' ] ), trim( $this->get_name() ) ) != 0 );

			return ( $time_is_changed || $name_is_changed );
		}
		else
		{
			if( $throw_exceptions >= AsException::THROW_DB )
			{
				throw new AsDbErrorException( 'Øvelsen som du prøver å oppdatere finnes ikke.' );
			}
			return false;
		}
	}

// ************************************************************************
/**
 *
 */
	public function delete_from_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( $this->exists_in_db() )
		{
			$customers = $this->get_customers();
			$competitors = $this->get_competitors();
			$oldname = $this->get_name_from_db();
			$oldtime = $this->get_time_from_db();

			$this->delete_orders_from_db();
			$this->delete_event_competitors_from_db();

			if( $mysqli = AsMySQLi::connect2db( 'Beklager, men øvelsen kunne ikke slettes på grunn av teknisk feil.' ) )
			{
				// Delete the event from the database.
				$query = "
				DELETE FROM " . TABLE_PREFIX . "event
				WHERE
				event_ID = " . $this->event_ID;

				if( $result = $mysqli->query( $query ) )
				{
					// If the data were successfully deleted from the database...
					if ( $mysqli->affected_rows < 1 )
					{
						$mysqli->close();

						if( $throw_exceptions >= AsException::THROW_DB )
						{
							throw new AsDbException( 'Øvelsen finnes ikke.' );
						}
						return false;
					}
					else
					{
						$mysqli->close();
						$this->send_cancellation_emails( $customers, $competitors, $oldname, $oldtime );
						return true;
					}
				}
				else
				{
					$mysqli->close();

					if( $throw_exceptions >= AsException::THROW_DB_ERROR )
					{
						throw new AsDbErrorException( 'Beklager, men øvelsen kunne ikke slettes på grunn av teknisk feil.' );
					}
					return false;
				}
			}
		}
	}

// ************************************************************************
/**
 *
 */
	public function delete_orders_from_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( $this->exists_in_db() )
		{
			if( $mysqli = AsMySQLi::connect2db( 'Beklager, men øvelsen kunne ikke slettes på grunn av teknisk feil.' ) )
			{
				// Delete the orders associated with the event from the database.
				$query = "
				DELETE FROM " . TABLE_PREFIX . "order
				WHERE
				event_ID = " . $this->event_ID;
				
				if( !( $result = $mysqli->query( $query ) ) )
				{
					$mysqli->close();

					if( $throw_exceptions >= AsException::THROW_DB_ERROR )
					{
						throw new AsDbErrorException( 'Beklager, men øvelsen kunne ikke slettes på grunn av teknisk feil.' );
					}
					return false;
				}
				else
				{
					$mysqli->close();
					return true;
				}
			}
		}
	}

// ************************************************************************
/**
 *
 */
	public function delete_event_competitors_from_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( $this->exists_in_db() )
		{
			if( $mysqli = AsMySQLi::connect2db( 'Beklager, men øvelsen kunne ikke slettes på grunn av teknisk feil.' ) )
			{
				// Delete the event_competitors associated with the event from the database.
				$query = "
				DELETE FROM " . TABLE_PREFIX . "event_competitor
				WHERE
				event_ID = " . $this->event_ID;
				
				if( !( $result = $mysqli->query( $query ) ) )
				{
					$mysqli->close();

					if( $throw_exceptions >= AsException::THROW_DB_ERROR )
					{
						throw new AsDbErrorException( 'Beklager, men øvelsen kunne ikke slettes på grunn av teknisk feil.' );
					}
					return false;
				}
				else
				{
					$mysqli->close();
					return true;
				}
			}
		}
	}

// ************************************************************************
/**
 * Checks whether the event exists in the database, and if so,
 * retrieves the correct event ID from the database and stores it
 * in the event object.
 *
 * Returns the event ID if the event is found in the database.
 * Returns false if the event is not found in the database.
 */
	protected function retrieve_from_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( ( $a = $this->exists_in_db( $throw_exceptions ) ) && is_array( $a ) )
		{
			$this->event_ID = $a[ 'event_ID' ];
			$this->time = $a[ 'time' ];
			$this->name = $a[ 'name' ];

			return $a;
		}
		else
		{
			return false;
		}
	} // End of function retrieve_from_db.

// ************************************************************************
/**
 * Checks if the event exists in the database.
 *
 * Returns the event's event_ID, time and name if the event exists in the database.
 * Returns false if the event doesn't exist in the database.
 */
	public function exists_in_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( $mysqli = AsMySQLi::connect2db( $technical_error ) )
		{
			$query = "";
		
			// Query to check if an identical event is already registered
			// in the database.
			if( isset( $this->event_ID ) )
			{
				$query = "
				SELECT event_ID, time, name
				FROM " . TABLE_PREFIX . "event
				WHERE
				event_ID = " . $this->event_ID;
			}
			elseif( isset( $this->time ) && isset( $this->name ) )
			{
				$query = "
				SELECT event_ID, time, name
				FROM " . TABLE_PREFIX . "event
				WHERE
				(UNIX_TIMESTAMP(time) - UNIX_TIMESTAMP('" . $this->time . "')) = 0
				AND
				UPPER(name) = UPPER('" . $mysqli->escape_data( $this->name ) . "')";
			}
			else
			{
				$mysqli->close();
				if( $throw_exceptions >= AsException::THROW_DB_ERROR )
				{
					throw new AsDbErrorException( $technical_error );
				}
				return false;
			}
			
			if( $result = $mysqli->query( $query ) )
			{
				// If the event is previously registered.
				if ( $result->num_rows > 0 )
				{
					$row = $result->fetch_assoc();
					$event = $row;
				
					$mysqli->close();
					return $event; 
				}
				else // Did NOT find the event in the database.
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
 * Validates the event data.
 *
 * Returns true if all validations were successful.
 * Returns an array with exception message(s) if one or more of the validations failed.
 */
	public function validate_data( $event_ID, $time, $name, $throw_exceptions = AsException::THROW_ALL )
	{
		$e = array();
		
		// Check for a valid event_ID.
		if ( isset( $event_ID ) )
		{
			if( $event_ID < 0 )
			{
				$e[ 'event' ] = 'Velg en øvelse!';
			}
		}
		else // If no event_ID was supplied.
		{
			// Check for a valid time format.
			if ( isset( $time ) && ( preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", trim( $time ) ) === 1 ) )
			{
				// Verify that the supplied time is in the future.
				if ( time() > strtotime( $time ) )
				{
					$e[ 'event_time' ] = 'Tidspunkt må være i fremtiden!';
				}
			}
			else
			{
				$e[ 'event_time' ] = 'Ugyldig tidspunkt!';
			}

			// Check for a valid name.
			if ( !isset( $name ) || ( preg_match( "/^[a-zæøå0-9\.\' \-]{2,45}$/i", trim( $name ) ) !== 1 ) )
			{
				$e[ 'event_name' ] = 'Ugyldig navn på øvelse!';
			}
		}

		if( count( $e ) > 0 )
		{
			if( $throw_exceptions >= AsException::THROW_VALIDATION )
			{
				throw new AsFormValidationException( $e );
			}
			return $e;
		}
		else
		{
			return true;
		}
	}

// ************************************************************************
/**
 * Validates the updated event data.
 *
 * Returns true if all validations were successful.
 * Returns an array with exception message(s) if one or more of the validations failed.
 */
	public function validate_update_data( $throw_exceptions = AsException::THROW_ALL )
	{
		$e = array();
		
		// Check for a valid event_ID.
		if ( isset( $this->event_ID ) )
		{
			if( $this->event_ID < 0 )
			{
				$e[ 'event' ] = 'Velg et arrangement!';
			}
			else // If no event_ID was supplied.
			{
				// Check for a valid time format.
				if ( isset( $this->time ) && ( preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}$/", trim( $this->time ) ) === 1 ) )
				{
					// Verify that the supplied time is in the future.
					if ( time() > strtotime( $this->time ) )
					{
						$e[ 'event_time' ] = 'Tidspunkt må være i fremtiden!';
					}
				}
				else
				{
					$e[ 'event_time' ] = 'Ugyldig tidspunkt!';
				}

				// Check for a valid name.
				if ( !isset( $this->name ) || ( preg_match( "/^[a-zæøå0-9\.\' \-]{2,45}$/i", trim( $this->name ) ) !== 1 ) )
				{
					$e[ 'event_name' ] = 'Ugyldig navn på øvelse!';
				}
			}

			if( count( $e ) > 0 )
			{
				if( $throw_exceptions >= AsException::THROW_VALIDATION )
				{
					throw new AsFormValidationException( $e );
				}
				return $e;
			}
			else
			{
				return true;
			}
		}
	}

// ************************************************************************
/**
 * $this->event_ID set function.
 */
	public function set_event_ID( $event_ID )
	{
		$this->event_ID = $event_ID;
	}

// ************************************************************************
/**
 * $this->event_ID get function.
 */
	public function get_event_ID()
	{
		return isset( $this->event_ID ) ? $this->event_ID : false;
	}

// ************************************************************************
/**
 * $this->time set function.
 */
	public function set_time( $time )
	{
		$this->time = $time;
	}

// ************************************************************************
/**
 * $this->time get function.
 */
	public function get_time()
	{
		return $this->time;
	}

// ************************************************************************
/**
 * $this->time get function.
 */
	public function get_time_from_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( ( $r = $this->exists_in_db( $throw_exceptions ) ) && is_array( $r ) )
		{
			return $r[ 'time' ];
		}
		return false;
	}

// ************************************************************************
/**
 * $this->name set function.
 */
	public function set_name( $name )
	{
		$this->name = $name;
	}

// ************************************************************************
/**
 * $this->name get function.
 */
	public function get_name()
	{
		return $this->name;
	}

// ************************************************************************
/**
 * $this->name get function.
 */
	public function get_name_from_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( ( $r = $this->exists_in_db( $throw_exceptions ) ) && is_array( $r ) )
		{
			return $r[ 'name' ];
		}
		return false;
	}

// ************************************************************************
/**
 * Customers of the current event get function.
 */
	public function get_customers()
	{
		if( $mysqli = AsMySQLi::connect2db( $technical_error ) )
		{
			$query = "
			SELECT u.user_ID, u.firstname, u.lastname, u.email, SUM(o.ticket_count) AS tickets
			FROM " . TABLE_PREFIX . "user u INNER JOIN " . TABLE_PREFIX . "order o
			ON u.user_ID = o.user_ID
			WHERE
			o.event_ID = " . $_POST[ 'event_ID' ] . "
			GROUP BY u.user_ID
			HAVING tickets > 0
			ORDER BY u.lastname, u.firstname";

			$result = $mysqli->query( $query );
			$r = array();
			
			if( $result->num_rows > 0 )
			{
				while( $row = $result->fetch_assoc() )
				{
					$r[] = $row;
				}
				
				$mysqli->close();
				return $r;
			}
			else
			{
				return false;
			}
		}
	}

// ************************************************************************
/**
 * Competitors of the current event get function.
 */
	public function get_competitors()
	{
		if( $mysqli = AsMySQLi::connect2db( $technical_error ) )
		{
			$query = "
			SELECT u.firstname, u.lastname, u.email, c.nationality
			FROM " . TABLE_PREFIX . "user u INNER JOIN " . TABLE_PREFIX . "competitor c
			ON u.user_ID = c.user_ID
			INNER JOIN " . TABLE_PREFIX . "event_competitor ec
			ON u.user_ID = ec.user_ID
			WHERE
			ec.event_ID = " . $this->event_ID . "
			ORDER BY u.lastname, u.firstname, c.nationality";

			$result = $mysqli->query( $query );
			$r = array();
			
			if( $result->num_rows > 0 )
			{
				while( $row = $result->fetch_assoc() )
				{
					$r[] = $row;
				}
				
				$mysqli->close();
				return $r;
			}
			else
			{
				return false;
			}
		}
	}

// ************************************************************************
/**
 * Customers of the current event get function.
 */
	public function get_tickets_sold()
	{
		if( $mysqli = AsMySQLi::connect2db( $technical_error ) )
		{
			$query = "
			SELECT e.event_ID, SUM(o.ticket_count) AS tickets
			FROM " . TABLE_PREFIX . "event e INNER JOIN " . TABLE_PREFIX . "order o
			ON e.event_ID = o.event_ID
			WHERE
			e.event_ID = " . $_POST[ 'event_ID' ] . "
			GROUP BY e.event_ID";

			$result = $mysqli->query( $query );
			$n = 0;
			
			if( $result->num_rows > 0 )
			{
				while( $row = $result->fetch_assoc() )
				{
					$n =+ $row[ 'tickets' ];
				}
			}
			$mysqli->close();
			return $n;
		}
	}

// ************************************************************************
/**
 * $this->validation_messages get function.
 */
	public function get_validation_messages()
	{
		$v = $this->validation_messages;
		unset( $this->validation_messages );
		return $v;
	}

// ************************************************************************

} // End of class Event.

// ************************************************************************

?>
