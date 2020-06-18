<form action="./register_event.php" method="post">
	<fieldset>
		<p>
			<b>Tidspunkt: <?php echo $this->event->get_time(); ?></b>
		</p>

		<p>
			<b>Navn: <?php echo $this->event->get_name(); ?></b>
		</p>
    
	</fieldset>

	<div align="center"><input type="button" name="previous" value="Avbryt" onclick="window.location='index.php?msg=Registreringen ble avbrutt.'" /> 
	<input type="button" name="previous" value="Tilbake" onclick="history.go(-1);" /> 
	<input type="submit" name="submit" value="Bekreft" /></div>

	<input type="hidden" name="event_time" value="<?php echo $this->event->get_time(); ?>" />
	<input type="hidden" name="event_name" value="<?php echo $this->event->get_name(); ?>" />

	<input type="hidden" name="confirmed" value="true" />
</form>
