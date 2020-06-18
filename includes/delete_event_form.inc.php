<form action="./delete_event.php" method="post">
	<fieldset>
<?php
	try
	{
		$event_list = new Event_List( Event_List::ALL_EVENTS, GUI::NO_FLOAT );
		$event_list->add_caption( 'Velg øvelse:' );
		$event_list->set_exception( isset( $this->validation_exceptions[ 'event' ] ) ? $this->validation_exceptions[ 'event' ] : NULL );
		$event_list->show();
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

	<div align="center"><input type="button" name="cancel" value="Avbryt" onclick="window.location='index.php?msg=Operasjonen ble avbrutt.'" /> 
	<input type="submit" name="submit" value="Slett øvelse" /></div>
	<div align="center"><div class="txtbg"><small>Du kan bekrefte slettingen i neste steg.</small></div></div>

    <input type="hidden" name="submitted" value="true" />
</form>
