<form action="./delete_event.php" method="post">
	<fieldset>
		<p>
			<b>Øvelse: <?php echo $this->event->get_name() . ', ' . date( "d.m.Y \k\l H:i", strtotime( $this->event->get_time() ) ); ?></b>
		</p>

	</fieldset>

	<div align="center"><input type="button" name="cancel" value="Avbryt" onclick="window.location='index.php?msg=Operasjonen ble avbrutt.'" /> 
	<input type="button" name="previous" value="Tilbake" onclick="history.go(-1);" /> 
	<input type="submit" name="submit" value="Bekreft" /></div>

	<input type="hidden" name="event_ID" value="<?php echo $this->event->get_event_ID(); ?>" />

	<input type="hidden" name="confirmed" value="true" />
</form>
