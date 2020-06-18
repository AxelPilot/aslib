<?php
include( './includes/head.inc.php' );
?>
<script type="text/javascript" src="./includes/forgot_password.js" charset="utf-8"></script>
<?php
include(  "includes/header.inc.php" );
echo '<h1>Nullstill passord</h1>';

$validation_exceptions = array();

if ( isset( $_POST[ 'submitted' ] ) ) // Check if the form has been submitted.
{
	try
	{
		// Connect to the database.
		$mysqli = AsMySQLi::connect2db( $technical_error );
	
		// Validate the email address.
		if ( empty( $_POST[ 'email' ] ) )
		{
			$uid = false;
			$validation_exceptions[ 'email' ] = 'Ugyldig e-postadresse!';
		}
		else
		{
			// Check for the existence of that email address in the database.
			$query = "
			SELECT user_ID
			FROM " . TABLE_PREFIX . "user
			WHERE
			email = '" . $mysqli->escape_data( $_POST[ 'email' ] ) . "'";
		
			$result = $mysqli->query( $query );
			if ( $result->num_rows == 1 )
			{
				// Retrieve the user ID.
				list( $uid ) = $result->fetch_array( MYSQLI_NUM );
			}
			else
			{
				$uid = false;
				$validation_exceptions[ 'email' ] = 'E-postadressen finnes ikke i systemet!';
			}
		}
	
		if ( $uid ) // If everyting is OK.
		{
			// Create a new, random password.
			$p = substr( md5( uniqid( rand(), 1 ) ), 3, 10 );
		
			// Make the query.
			$query = "
			UPDATE " . TABLE_PREFIX . "user
			SET password = SHA('". $p . "')
			WHERE
			user_ID = " . $uid;
		
			$result = $mysqli->query( $query );
		
			if ( $mysqli->affected_rows == 1 ) // If it ran OK.
			{
				// Send an email.
				$body = "Passordet ditt for å logge på Ski VM har blit endret til: " . $p . "\r\n\r\n";
				$body .= "Vennligst logg inn med dette passordet." . "\r\n\r\n";
				$body .= "Når du er logget inn kan du endre passordet som du ønsker ved å bruke lenken \"Endre passord\".";
			
				$email = new Email( $_POST[ 'email' ], 'Ditt midlertidige passord.', $body );
				$email->send();
			
				$mysqli->close(); // Close the database connection.
				redirect( 'index.php?msg=Passordet ditt har blitt endret.<br /><br />Et midlertidig passord er sendt til din registrerte e-postadresse.<br />Etter at du har logget inn med det midlertidige passordet kan du endre det som du ønsker ved å bruke lenken "Endre passord".' );
				exit();
			}
			else
			{
				redirect( 'Beklager, men passordet ditt kunne ikke nullstilles på grunn av teknisk feil.' );
				exit();
			}
		}
	
		$mysqli->close();
	}
	catch( AsDbErrorException $e )
	{
		echo '<div class="Error">' . $e->getAsMessage() . '</div>';
		echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';
	}
} // End of the main Submit conditional.
?>

<div class="txtbg2" style="margin-bottom:20px;">Oppgi e-postadressen din for å motta instruksjoner om å nullstille passordet ditt.</div>

<form action="forgot_password.php" method="post">
	<fieldset>

        <div class="FormField NoFloat">
			<b>E-postadresse:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
				<input type="text" id="email" name="email" size="40" maxlength="40"<?php
				if ( isset( $_POST[ 'email' ] ) )
				{
					echo ' value="' . $_POST[ 'email' ] . '"';
				}
			?> style="width:272px;<?php
				if( isset( $validation_exceptions[ 'email' ] ) )
				{
					echo ' border-color: #F00;';
				}
			?>" />
			</div>

			<div id="email_exception" class="validation_exception NoFloat">
				<?php 
					if( isset( $validation_exceptions[ 'email' ] ) )
					{
						echo $validation_exceptions[ 'email' ];
					}
				?>
			</div>
		</div>

	</fieldset>

    <div align="center">
    	<input type="submit" name="submit" value="Nullstill passord" />
	</div>

   	<input type="hidden" name="submitted" value="TRUE" />
</form>


<?php
include( "includes/footer.inc.php" );
?>    