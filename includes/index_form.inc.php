<form action="./index.php" method="post">
<?php
	try
	{
		$event_list = new Event_List( Event_List::ALL_EVENTS, GUI::FLOAT_LEFT );
		$event_list->show();

		$submit = new Button( 'View' );
		$submit->add_style( "margin:2px;" );
		$submit->show();
	}
	catch( AsDbErrorException $e )
	{
		echo '<div class="Error">' . $e->getAsMessage() . '</div>';
		echo '<p><div class="Error">Please try again later.</div></p>';
	}
	catch( AsDbException $e )
	{
		echo '<p><div class="Error">' . $e->getAsMessage() . '</div></p>';
		echo '<p><div class="Error">Please try again later.</div></p>';
	}	
?>

<?php
if ( isset( $_POST[ 'submitted' ] ) && isset( $_POST[ 'event_ID' ] ) && ( $_POST[ 'event_ID' ] >= 0 ) )
{
	try
	{
		$event = new Event( $_POST[ 'event_ID' ] );
		$competitors = $event->get_competitors();
		$customers = $event->get_customers();
	}
	catch( AsDbErrorException $e )
	{
		echo '<div class="Error">' . $e->getAsMessage() . '</div>';
		echo '<p><div class="Error">Please try again later.</div></p>';
	}
	catch( AsDbException $e )
	{
		echo '<p><div class="Error">' . $e->getAsMessage() . '</div></p>';
		echo '<p><div class="Error">Please try again later.</div></p>';
	}	
	catch( AsFormValidationException $e )
	{
		$errors = $e->getAsMessage();
		foreach( $errors as $value )
		{
			echo '<div class="Error">' . $value . '</div>';
		}
		unset( $value );

		echo '<p><div class="Error">Please try again.</div></p>';
	}

	echo '<div id="TicketsSold">Tickets Sold: ' . $event->get_tickets_sold() . '</div>';
	
	echo '
	<table border="1" cellpadding="5" cellspacing="0">
		<thead>
			<tr>
				<th colspan="3" align="center">
					Contestants
				</th>
				<th colspan="3" align="center">
					Sold Tickets
				</th>
			</tr>

			<tr>
				<th>
					First Name
				</th>
				<th>
					Last Name
				</th>
				<th>
					Nationality
				</th>
				<th>
					First Name
				</th>
				<th>
					Last Name
				</th>
				<th>
					Tickets
				</th>
			</tr>
		</thead>';
						
	if( $competitors || $customers )
	{
		echo '<tbody>' . "\r\n";
		$n = 0;
		while( ( $n < count( $competitors ) ) || ( $n < count( $customers ) ) )
		{
			echo '<tr>' . "\r\n";

			echo '<td>' . "\r\n";
			echo $n < count( $competitors ) ? $competitors[ $n ][ 'firstname' ] : "";
			echo '</td>' . "\r\n";

			echo '<td>' . "\r\n";
			echo $n < count( $competitors ) ? $competitors[ $n ][ 'lastname' ] : "";
			echo '</td>' . "\r\n";

			echo '<td>' . "\r\n";
			echo $n < count( $competitors ) ? $competitors[ $n ][ 'nationality' ] : "";
			echo '</td>' . "\r\n";

			echo '<td>' . "\r\n";
			echo $n < count( $customers ) ? $customers[ $n ][ 'firstname' ] : "";
			echo '</td>' . "\r\n";

			echo '<td>' . "\r\n";
			echo $n < count( $customers ) ? $customers[ $n ][ 'lastname' ] : "";
			echo '</td>' . "\r\n";

			echo '<td align="right">' . "\r\n";
			echo $n < count( $customers ) ? $customers[ $n ][ 'tickets' ] : "";
			echo '</td>' . "\r\n";

			echo '</tr>';
			$n++;
		}
		echo '</tbody>' . "\r\n";
		echo '</table>';
	}
}
?>

    <input type="hidden" name="submitted" value="true" />
</form>
