<form action="./purchase_ticket.php" method="post">
	<fieldset>
		<p>
			<b>Øvelse: <?php echo $this->order->get_event()->get_name() . ', ' . date( "d.m.Y \k\l H:i", strtotime( $this->order->get_event()->get_time() ) ); ?></b>
		</p>

		<p>
			<b>Antall billetter: <?php echo $this->order->get_ticket_count(); ?></b>
		</p>

		<p>
			<b>Bestillingen er utført: <?php echo date( "d.m.Y \k\l H:i", $this->order->get_timestamp() ); ?></b>
		</p>
	</fieldset>

	<div align="center"><input type="button" name="cancel" value="Avbryt" onclick="window.location='index.php?msg=Bestillingen ble avbrutt.'" /> 
	<input type="button" name="previous" value="Tilbake" onclick="history.go(-1);" /> 
	<input type="submit" name="submit" value="Bekreft bestilling" /> </div>

	<input type="hidden" name="event_ID" value="<?php echo $this->order->get_event()->get_event_ID(); ?>" />
	<input type="hidden" name="ticket_count" value="<?php echo $this->order->get_ticket_count(); ?>" />
	<input type="hidden" name="timestamp" value="<?php echo $this->order->get_timestamp(); ?>" />

	<input type="hidden" name="confirmed" value="TRUE" />
