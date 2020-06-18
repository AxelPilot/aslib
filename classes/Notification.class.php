<?php

// ************************************************************************

class Notification extends Entity
{

// ************************************************************************

	protected $notification_ID = NULL;
	protected $recipients = NULL;
	protected $title = NULL;
	protected $message = NULL;
	protected $url = '#';
	protected $params = NULL;
	protected $registration_date = NULL;

// ************************************************************************
/**
 *
 */
	public function __construct(	$notification_ID, 
									$recipients = NULL, 
									$title = NULL, 
									$message = NULL, 
									$url = "#", 
									$params = NULL, 
									$throw_exceptions = AsException::THROW_ALL )
	{
		// Retrieve an existing notification from the database identified by
		// the given $notification_ID.
		if( isset( $notification_ID ) )
		{
			$this->notification_ID = $notification_ID;
			if( !$this->retrieve_from_db( $throw_exceptions ) )
			{
				if( $throw_exceptions >= AsException::THROW_DB )
				{
					throw new AsDbException( 'Beskjeden finnes ikke i systemet.' );
				}
			}
		}
		
		// Create a new notification from the given $recipients, $title, $message, $url and $params.
		elseif( isset( $recipients ) && isset( $title ) && isset( $message ) && isset( $url ) )
		{
			$this->recipients = !is_array( $recipients ) ? array( $recipients ) : $recipients;
			$this->title = $title;
			$this->message = $message;
			$this->url = $url;
			$this->params = $params;
			$this->save_to_db( $throw_exceptions );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function save_to_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( $this->exists_in_db( $throw_exceptions ) )
		{
			return $this->update_db( $throw_exceptions );
		}		
		elseif( isset( $this->recipients ) && isset( $this->title ) && isset( $this->message ) && isset( $this->url ) )
		{
			if ( $mysqli = AsMySQLi::connect2db( $technical_error, $throw_exceptions ) )
			{
				$mysqli->autocommit( false );
				$ok = true;
				$notification_ID = NULL;
				
				// Query to add the notification to the database.
				$query = "
				INSERT INTO " . TABLE_PREFIX . "notification (title, message, url, params, registration_date) 
				VALUES ('" . $mysqli->escape_data( $this->title ) . "', 
				'" . $mysqli->escape_data( $this->message ) . "', 
				'" . $mysqli->escape_data( $this->url ) . "', 
				" . ( isset( $this->params ) ? "'" . $mysqli->escape_data( $this->params ) . "'" : "NULL" ) . ", 
				NOW())";
				
				// If the data were successfully inserted into the database...
				if( $mysqli->query( $query ) && ( $mysqli->affected_rows == 1 ) )
				{
					$notification_ID = $mysqli->insert_id;

					foreach( $this->recipients as $recipient )
					{
						// Build the query to link the notification to its user(s).
						$query = "
						INSERT INTO " . TABLE_PREFIX . "user_has_notification (notification_ID, user_ID) 
						VALUES ('" . $mysqli->escape_data( $notification_ID ) . "', 
						'" . $mysqli->escape_data( $recipient->get_user_ID() ) . "')";
						
						if( !$mysqli->query( $query ) || ( $mysqli->affected_rows == 0 ) )
						{ // If query was unsuccessful.
							$ok = false;
						}
					}
				}
				else // If query was unsuccessful.
				{
					$ok = false;
				}

				if( $ok ) // Commit.
				{
					$mysqli->commit();
					$this->notification_ID = $notification_ID;
					$mysqli->close();
					return $this->notification_ID;
				}
				else // Rollback.
				{
					$mysqli->rollback();
					if( $throw_exceptions >= AsException::THROW_DB_ERROR )
					{
						throw new AsDbErrorException( $technical_error );
					}
					$mysqli->close();
					return false;
				}
			}
		}
	}

// ************************************************************************
/**
 *
 */
	protected function has_changed( $data = NULL )
	{
		$changed = false;
		$d = isset( $data ) && $data ? $data : $this->exists_in_db();
		
		if( $this->title != $d[ 'title' ] )
		{
			$changed = true;
		}
		
		if( $this->message != $d[ 'message' ] )
		{
			$changed = true;
		}
		
		if( $this->url != $d[ 'url' ] )
		{
			$changed = true;
		}
		
		if( $this->params != $d[ 'params' ] )
		{
			$changed = true;
		}
		
		return $changed;
	}

// ************************************************************************
/**
 * NB: Does not update recipients!
 */
	protected function update_db( $throw_exceptions = AsException::THROW_ALL )
	{
		$ok = false;
		$data = $this->exists_in_db();
		
		if( $data && $this->has_changed( $data ) )
		{
			if ( $mysqli = AsMySQLi::connect2db( $technical_error, $throw_exceptions ) )
			{
				// Query to update the notification in the database.
				$query = "
				UPDATE " . TABLE_PREFIX . "notification";
				
				$query .= "
				SET ";
				$query .= $this->title != $data[ 'title' ] ? "title = '" . $this->title . "' " : "";
				$query .= $this->message != $data[ 'message' ] ? "message = '" . $this->message . "' " : "";
				$query .= $this->url != $data[ 'url' ] ? "url = '" . $this->url . "' " : "";
				$query .= $this->params != $data[ 'params' ] ? "params = '" . $this->params . "' " : "";
				
				$query .= "
				WHERE
				notification_ID = " . $this->notification_ID;

				// If the database was successfully updated.
				if( $mysqli->query( $query ) && ( $mysqli->affected_rows == 1 ) )
				{
					$ok = true;
				}
				else // Update failed.
				{
					if( $throw_exceptions >= AsException::THROW_DB_ERROR )
					{
						$mysqli->close();
						throw new AsDbErrorException( $technical_error );
					}
				}
				$mysqli->close();
			}
		}
		return $ok;
	}

// ************************************************************************
/**
 *
 */
	public function send_by_email()
	{
		$ok = true;
		if( $this->exists_in_db() )
		{
			if( isset( $this->recipients ) )
			{
				$message = 'Du har mottatt en ny beskjed fra Ski-VM på Liksom:' . "\r\n\r\n";
				$message .= $this->message . "\r\n\r\n";
					
				$footer = 'Mvh' . "\r\n";
				$footer .= 'Ski-VM på Liksom' . "\r\n";
										
				if( count( $this->recipients ) > 0 )
				{
					foreach( $this->recipients as $recipient )
					{
						$greeting = 'Kjære ' . $recipient->get_firstname() . ' ' . $recipient->get_lastname() . "!\r\n\r\n";
						$mail_url = getBaseUrl() . "/open_notification.php?x=" . $this->notification_ID . "&y=" 
							. $recipient->get_user_ID() . "\r\n\r\n";
						
						$email = new Email( $recipient->get_email(), $this->title, $greeting . $message . $mail_url . $footer );
						if( !$email->send() )
						{
							$ok = false;
						}
					}
				}
			}
		}
		else
		{
			$ok = false;
		}
		return $ok;
	}

// ************************************************************************
/**
 *
 */
	public function mark_as_read( $user_ID, $throw_exceptions = AsException::THROW_ALL )
	{
		$ok = false;
		if( $this->exists_in_db( $throw_exceptions ) 
			&& ( $mysqli = AsMySQLi::connect2db( $technical_error ) ) )
		{
			// Update the event in the database.
			$query = "
			UPDATE " . TABLE_PREFIX . "user_has_notification
			SET opened_time = NOW()
			WHERE
			notification_ID = " . $this->notification_ID . "
			AND
			user_ID = " . $user_ID;
			
			// If the data were successfully inserted into the database...
			if ( $mysqli->query( $query ) && ( $mysqli->affected_rows == 1 ) )
			{
				$ok = true;
			}
			else // If query was unsuccessful.
			{
				if( $throw_exceptions >= AsException::THROW_DB_ERROR )
				{
					$mysqli->close();
					throw new AsDbErrorException( $technical_error );
				}
			}
			$mysqli->close();
		}
		return $ok;
	}

// ************************************************************************
/**
 * Checks whether the user exists in the database, and if so,
 * retrieves the correct user ID from the database and stores it
 * in the user object.
 *
 * Returns the user ID if the event is found in the database.
 * Returns false if the user is not found in the database.
 */
	protected function retrieve_from_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( ( $a = $this->exists_in_db( $throw_exceptions ) ) && is_array( $a ) )
		{
			$this->notification_ID = $a[ 'notification_ID' ];
			$this->recipients = $a[ 'recipients' ];
			$this->title= $a[ 'title' ];
			$this->message = $a[ 'message' ];
			$this->url = $a[ 'url' ];
			$this->params = $a[ 'params' ];
			$this->registration_date = $a[ 'registration_date' ];

			return $a;
		}
		else
		{
			return false;
		}
	} // End of function retrieve_from_db.

// ************************************************************************
/**
 * Checks if the notification exists in the database.
 */
	public function exists_in_db( $throw_exceptions = AsException::THROW_ALL )
	{
		$ok = true;
		$notification = array();

		if( !isset( $this->notification_ID ) )
		{
			$ok = false;
		}
		else
		{
			if( $mysqli = AsMySQLi::connect2db( $technical_error, $throw_exceptions ) )
			{
				
				// Locating the notification in the database.
				$query = "
				SELECT notification_ID, title, message, url, params, registration_date
				FROM " . TABLE_PREFIX . "notification
				WHERE 
				notification_ID = " . $this->notification_ID;
				
				if( $result = $mysqli->query( $query ) )
				{
					// Found the notification in the database.
					if ( $result->num_rows > 0 )
					{
						$row = $result->fetch_assoc();
						$notification = $row;
						$result->free();
					
						// Query to find users associated with the notification.
						$query = "
						SELECT user_ID
						FROM " . TABLE_PREFIX . "user_has_notification
						WHERE 
						notification_ID = " . $this->notification_ID;
				
						if( $result = $mysqli->query( $query ) )
						{
							if ( $result->num_rows > 0 )
							{
								while( $row = $result->fetch_assoc() )
								{
									$notification[ 'recipients' ][] = new User( $row[ 'user_ID' ] );
								}
							}
						}
					}
					else // Did NOT find the notification.
					{
						$ok = false;
					}
					$result->close();
				}
				else
				{
					if( $throw_exceptions >= AsException::THROW_DB_ERROR )
					{
						$mysqli->close();
						throw new AsDbErrorException( $technical_error );
					}
					$ok = false;
				}
				$mysqli->close();
			}
		}
		return $ok ? $notification : false;
	} // End of function exists_in_db.

// ************************************************************************
/**
 *
 */
	public function delete_from_db( $throw_exceptions = AsException::THROW_ALL )
	{
		$ok = false;
		if( $this->exists_in_db( $throw_exceptions ) )
		{
			if( $this->delete_recipients_from_db( $throw_exceptions ) )
			{
				if( $mysqli = AsMySQLi::connect2db( 'Beklager, men beskjeden kunne ikke slettes på grunn av teknisk feil.', $throw_exceptions ) )
				{
					// Query to delete the notification from the database.
					$query = "
					DELETE FROM " . TABLE_PREFIX . "notification
					WHERE
					notification_ID = " . $this->notification_ID;
	
					if( $mysqli->query( $query ) )
					{
						// If the data were successfully deleted from the database...
						if ( $mysqli->affected_rows == 1 )
						{
							$ok = true;
						}
						else
						{
							if( $throw_exceptions >= AsException::THROW_DB )
							{
								$mysqli->close();
								throw new AsDbException( 'Beskjeden finnes ikke.' );
							}
						}
					}
					else
					{
						if( $throw_exceptions >= AsException::THROW_DB_ERROR )
						{
							$mysqli->close();
							throw new AsDbErrorException( 'Beklager, men beskjeden kunne ikke slettes på grunn av teknisk feil.' );
						}
					}
					$mysqli->close();
				}
			}
		}
		else
		{
			if( $throw_exceptions >= AsException::THROW_DB )
			{
				throw new AsDbException( 'Beskjeden finnes ikke.' );
			}
		}
		return $ok;
	}

// ************************************************************************
/**
 *
 */
	protected function delete_recipients_from_db( $throw_exceptions = AsException::THROW_ALL )
	{
		$ok = false;
		if( $this->exists_in_db() )
		{
			if( $mysqli = AsMySQLi::connect2db( 'Beklager, men beskjeden kunne ikke slettes på grunn av teknisk feil.' ) )
			{
				// Delete the recipients associated with the notification from the database.
				$query = "
				DELETE FROM " . TABLE_PREFIX . "user_has_notification
				WHERE
				notification_ID = " . $this->notification_ID;
				
				if( $mysqli->query( $query ) )
				{
					$ok = true;
				}
				else
				{
					if( $throw_exceptions >= AsException::THROW_DB_ERROR )
					{
						$mysqli->close();
						throw new AsDbErrorException( 'Beklager, men beskjeden kunne ikke slettes på grunn av teknisk feil.' );
					}
				}
				$mysqli->close();
			}
		}
		return $ok;
	}

// ************************************************************************
/**
 *
 */
	public function get_notification_ID()
	{
		return $this->notification_ID;
	}

// ************************************************************************
/**
 *
 */
	public function get_recipients()
	{
		return $this->recipients;
	}

// ************************************************************************
/**
 *
 */
	public function get_title()
	{
		return $this->title;
	}

// ************************************************************************
/**
 *
 */
	public function get_message()
	{
		return $this->message;
	}

// ************************************************************************
/**
 *
 */
	public function get_url()
	{
		return $this->url;
	}

// ************************************************************************
/**
 *
 */
	public function set_params( $params )
	{
		$ok = true;
		$this->params = $params;
		if( $this->exists_in_db() )
		{
			if( !$this->save_to_db() )
			{
				$ok = false;
			}
		}
		return $ok;
	}

// ************************************************************************
/**
 *
 */
	public function get_params()
	{
		return $this->params;
	}

// ************************************************************************
/**
 *
 */
	public function get_registration_date()
	{
		return $this->registration_date;
	}

// ************************************************************************
/**
 *
 */
	public static function convert_text2html( $str )
	{
		// Order of replacement
		$order = array( "\r\n", "\n", "\r" );
		$replace = '<br />';
		
		// Processes \r\n's first so they aren't converted twice.
		$newstr = str_replace( $order, $replace, $str );
		return $newstr;
	}
	
// ************************************************************************

} // End of class Notification.

// ************************************************************************

?>
