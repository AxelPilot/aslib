<?php

// ************************************************************************
/**
 *
 */
class Admin_Request
{

// ************************************************************************

	protected $applicant;
	protected $activation_code;
	protected $admins;

// ************************************************************************

	const APPROVED = true;
	const DENIED = false;

// ************************************************************************
/**
 *
 */
	public function __construct( $applicant, $throw_exceptions = AsException::THROW_ALL )
	{
		$this->applicant = $applicant;
	}

// ************************************************************************
/**
 *
 */
	public function apply( $throw_exceptions = AsException::THROW_ALL )
	{
		$ok = false;
		if( $this->generate_activation_code_and_save_to_db( $throw_exceptions ) && ( $this->admins = $this->get_admins() ) )
		{
			$title = 'Administrator-forespørsel';
			$message = $this->applicant->get_firstname() . ' ' . $this->applicant->get_lastname(); 
			$message .= ' ønsker å bli godkjent som administrator.';
			$url = getBaseUrl() . '/process_admin_request.php';
			$params = 'x=' . $this->applicant->get_user_ID() . '&y=' . $this->activation_code;
			
			$notification = new Notification( NULL, $this->admins, $title, $message, $url, $params, $throw_exceptions );
			$notification->set_params( $notification->get_params() . "&nid=" . $notification->get_notification_ID() );
			$notification->send_by_email();

			$title = 'Din admin-søknad er sendt';
			$message = 'Din administrator-søknad er sendt til behandling.' . "\r\n";
			$message .= 'Du vil få beskjed pr e-post så snart søkanden er ferdig behandlet.' . "\r\n\r\n";
			$message .= 'Mvh' . "\r\n";
			$message .= 'Liksom-Ski-VM' . "\r\n";
			$notification = new Notification( NULL, $this->applicant, $title, $message, '#', NULL, $throw_exceptions );
			$notification->send_by_email();

			$ok = true;
		}
		return $ok;
	}

// ************************************************************************
/**
 *
 */
	public function approve( $activation_code, $approved = self::APPROVED, $throw_exceptions = AsException::THROW_ALL )
	{
		$ok = false;
		if ( $mysqli = AsMySQLi::connect2db( $technical_error, $throw_exceptions ) )
		{
			$query = "
			UPDATE " . TABLE_PREFIX . "user
			SET activation_admin = NULL, " . ( $approved == self::APPROVED ? "admin = 'Y'" : "admin = 'N'" ) . "
			WHERE
			user_ID = " . $this->applicant->get_user_ID() . " 
			AND 
			activation_admin = '" . $mysqli->escape_data( $activation_code ) . "'
			LIMIT 1";
			
			if( $mysqli->query( $query ) && ( $mysqli->affected_rows == 1 ) )
			{
				$ok = true;
			}
			elseif( $throw_exceptions >= AsException::THROW_DB_ERROR )
			{
				$mysqli->close();
				throw new AsDbErrorException( $technical_error );
			}
			$mysqli->close();
		}
		
		if( $ok )
		{
			$notification_title = $approved ? 
				"Godkjent som administrator" : 
				"Administrator-søknad avslått";
				
			$notification_message = $approved ? 
				"Din søknad om å bli administrator har blitt godkjent." : 
				"Din søknad om å bli administrator har blitt avslått.";

			$notification = new Notification( NULL, $this->applicant, $notification_title, 
				$notification_message, "#", NULL, $throw_exceptions );
			$notification->send_by_email();
		}
		return $ok;
	}

// ************************************************************************
/**
 *
 */
	protected function generate_activation_code_and_save_to_db( $throw_exceptions = AsException::THROW_ALL )
	{
		$ok = false;
		if( !$this->applicant->is_admin() )
		{
			$user_ID = $this->applicant->get_user_ID();
			$email = $this->applicant->get_email();
			$this->activation_code = $this->applicant->create_activation_code();

			if ( $mysqli = AsMySQLi::connect2db( $technical_error, $throw_exceptions ) )
			{
				$query = "
				UPDATE " . TABLE_PREFIX . "user
				SET activation_admin = '" . $mysqli->escape_data( $this->activation_code ) . "'
				WHERE ";
				$query .= isset( $user_ID ) ? 
					"user_ID = " . $user_ID : 
					"UPPER(email) = UPPER('" . $mysqli->escape_data( $email ) . "')";
			
				if( $mysqli->query( $query ) && ( $mysqli->affected_rows == 1 ) )
				{
					$ok = true;
				}
				elseif( $throw_exceptions >= AsException::THROW_DB_ERROR )
				{
					$mysqli->close();
					throw new AsDbErrorException( $technical_error );
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
	protected function get_admins( $throw_exceptions = AsException::THROW_ALL )
	{
		$ok = false;
		$admins = array();
		if( $mysqli = AsMySQLi::connect2db( $technical_error, $throw_exceptions ) )
		{
			$query = "
			SELECT user_ID
			FROM " . TABLE_PREFIX . "user
			WHERE
			admin = 'Y'
			ORDER BY lastname, firstname";

			if( $result = $mysqli->query( $query ) )
			{
			
				if( $result->num_rows > 0 )
				{
					while( $row = $result->fetch_assoc() )
					{
						$admins[] = new User( $row[ 'user_ID' ] );
					}
					$ok = true;
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
			}
		}
		return $ok ? $admins : false;
	}

// ************************************************************************

} // End of class Admin_Request.

// ************************************************************************

?>
