<?php

// ************************************************************************

class Competitor extends User
{
	protected $nationality;

// ************************************************************************

	public function __construct(	$user_ID,
									$email = NULL,
									$nationality = NULL, 
									$lastname = NULL, 
									$firstname = NULL, 
									$address = NULL, 
									$postal_code = NULL, 
									$city = NULL, 
									$phone = NULL, 
									$throw_exceptions = AsException::THROW_ALL )
	{
		if( isset( $user_ID ) )
		{
			parent::__construct( $user_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, AsException::THROW_NONE );
		}
		elseif( isset( $email ) )
		{
			parent::__construct( NULL, $email, NULL, NULL, $lastname, $firstname, $address, $postal_code, $city, $phone, AsException::THROW_NONE );
		}

		if( !( $this->nationality = $this->is_competitor( $throw_exceptions ) ) )
		{
			$v = $this->validate_nationality( $nationality, AsException::THROW_NONE );
			if( $v === true )
			{
				$this->nationality = $nationality;
			}
			elseif( is_array( $v ) && ( $throw_exceptions < AsException::THROW_VALIDATION ) )
			{
				$this->validation_messages = array_merge( $this->validation_messages, $v );
			}
		}
	}

// ************************************************************************
/**
 * Saves the competitor to the database if the user isn't already in the
 * database.
 *
 * Returns the user_ID upon success.
 * Returns false upon failure.
 */
	public function save_competitor_to_db( $throw_exceptions = AsException::THROW_ALL )
	{
		if( !$this->exists_in_db( $throw_exceptions ) )
		{
			$this->save_to_db( $throw_exceptions );
		}
		
		if( !$this->is_competitor() )
		{
			if ( $mysqli = AsMySQLi::connect2db( $technical_error ) )
			{
				// Add the competitor to the database.
				$query = "
				INSERT INTO " . TABLE_PREFIX . "competitor (user_ID, nationality) 
				VALUES ('" . $this->user_ID . "', 
				'" . $mysqli->escape_data( $this->nationality ) . "')";

				if( $result = $mysqli->query( $query ) )
				{
					// If the data were successfully inserted into the database...
					if ( $mysqli->affected_rows == 1 )
					{
						$mysqli->close();

						// Returns the user_ID.
						return $this->user_ID;
					}
					else // If query was unsuccessful.
					{
						$mysqli->close();
						return false;
					}
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
			}
		}
		else
		{
			return false;
		}
	} // End of function save_to_db().

// ************************************************************************
/**
 *
 */
	public function is_competitor( $throw_exceptions = AsException::THROW_ALL )
	{
		if( $mysqli = AsMySQLi::connect2db( $technical_error ) )
		{
			// Checking if the user is already registered in the database as a competitor.
			$query = "
			SELECT u.user_ID, nationality
			FROM " . TABLE_PREFIX . "user u
			INNER JOIN " . TABLE_PREFIX . "competitor c
			ON u.user_ID = c.user_ID
			WHERE
			UPPER(u.email) = UPPER('" . $mysqli->escape_data( $this->email ) . "')";
			
			if( $result = $mysqli->query( $query ) )
			{
				// If the user is previously registered as a competitor.
				if ( $result->num_rows == 1 )
				{
					$row = $result->fetch_assoc();
					$nationality = $row[ 'nationality' ];
				
					$mysqli->close();
					return $nationality; 
				}
				else // Did NOT find the user as a competitor in the database.
				{
					$mysqli->close();
					return false;
				}
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
		}
	}

// ************************************************************************
/**
 * Validates the competitor nationality.
 *
 * Returns true if all validations were successful.
 * Returns an array with exception message(s) if one or more of the validations failed.
 */
	public function validate_nationality( $nationality, $throw_exceptions = AsException::THROW_ALL )
	{
		$e = array();
		
		// Check for nationality.
		if( !isset( $nationality ) || ( preg_match( "/^[a-zæøå\.\' \-]{2,40}$/i", trim( $nationality ) ) !== 1 ) )
		{
			$e[ 'nationality' ] = 'Vennligst oppgi nasjonalitet!';
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
 * Nationality get function.
 */
	public function get_nationality()
	{
		return $this->nationality;
	}

// ************************************************************************

}
?>