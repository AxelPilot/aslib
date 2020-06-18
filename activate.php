<?php
include( './includes/head.inc.php' );
include(  "includes/header.inc.php" );
echo '<h1>Aktivering av konto</h1>';

// Validate $_GET[ 'x' ] and $_GET[ 'y' ].
if ( isset( $_GET[ 'x' ] ) )
{
	$x = ( int ) $_GET[ 'x' ];
}
else
{
	$x = 0;
}

if ( isset( $_GET[ 'y' ] ) )
{
	$y = ( string ) $_GET[ 'y' ];
}
else
{
	$y = 0;
}

// If $x and $y aren't correct, redirect the user.
if ( ( $x > 0 ) && ( strlen( $y ) == 32 ) )
{
	try
	{
		// Connect to the database.
		$mysqli = AsMySQLi::connect2db( $en_technical_error );
		$query = "
		UPDATE " . TABLE_PREFIX . "user 
		SET activation_code=NULL 
		WHERE
		(user_ID=" . $x . " AND activation_code='" . $mysqli->escape_data( $y ) . "')
		LIMIT 1";
	
		$result = $mysqli->query( $query );
	
		// Print a customized message.
		if ( $mysqli->affected_rows == 1 )
		{
			redirect( "login.php?msg=" . $account_is_activated );
			exit();
		}
		else
		{
			redirect( "index.php?msg=" . $account_couldnt_be_activated );
			exit();
		}
	
		$mysqli->close();
	}
	catch( AsDbErrorException $e )
	{
		redirect( 'index.php?msg=' . $e->getAsMessage() );
		exit();
	}
}
else
{
	redirect( 'index.php?msg=' . $account_couldnt_be_activated );
	exit();
} // End of main IF-ELSE.


include( "includes/footer.inc.php" );
?>
