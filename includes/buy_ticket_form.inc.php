<link href="layout.css" rel="stylesheet" type="text/css" />
<form action="./purchase_ticket.php" method="post">
	<fieldset>

<?php
	try
	{
		$el = new Event_List( Event_List::ALL_EVENTS, GUI::NO_FLOAT );
		$el->add_caption( 'Øvelse:' );
		$el->set_exception( isset( $this->validation_exceptions[ 'event' ] ) ? $this->validation_exceptions[ 'event' ] : NULL );
		$el->show();

		$ts = new Ticket_selector( "Antall billetter:" );
		$ts->show();
	}
	catch( AsDbErrorException $e )
	{
		echo '<div class="Error">' . $e->getAsMessage() . '</div>';
		echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';
	}
	catch( AsDbException $e )
	{
		echo '<p><div class="Error">' . $e->getAsMessage() . '</div></p>';
		echo '<p><div class="Error">Vennligst prøv igjen senere.</div></p>';
	}	
?>
	</fieldset>
    
	<div align="center"><input type="button" name="cancel" value="Avbryt" onclick="window.location='index.php?msg=Bestillingen ble avbrutt.'" /> 
    <input type="submit" name="submit" value="Utfør bestilling" /></div>
	<div align="center"><div class="txtbg"><small>Du kan bekrefte bestillingen i neste steg.</small></div></div>

    <input type="hidden" name="submitted" value="true" />
</form>
