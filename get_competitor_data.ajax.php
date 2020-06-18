<?php
require_once './includes/ajax_header.inc.php';

if( is_loggedIn( true ) )
{
	$result = array();
	if( isset( $_GET[ 'email' ] ) )
	{
		$email = trim( $_GET[ 'email' ] );
		try
		{
			$competitor = new Competitor( NULL, $email );
			if( $competitor->exists_in_db() )
			{
				$result[ 'exists' ] = true;
				$result[ 'firstname' ] = $competitor->get_firstname();
				$result[ 'lastname' ] = $competitor->get_lastname();
				$result[ 'address' ] = $competitor->get_address();
				$result[ 'postal_code' ] = $competitor->get_postal_code();
				$result[ 'city' ] = $competitor->get_city();
				$result[ 'phone' ] = $competitor->get_phone();
				
				if( $competitor->is_competitor() )
				{
					$result[ 'nationality' ] = $competitor->get_nationality();
				}
			}
			else
			{
				$result[ 'exists' ] = false;
			}
		}
		catch( AsDbErrorException $e )
		{
		}
		catch( AsDbException $e )
		{
		}	
		catch( AsFormValidationException $e )
		{
			$result[ 'validation_exception' ] = $e->getAsMessage();
		}

		echo json_encode( $result );
	}
}
?>