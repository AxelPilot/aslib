<form action="./register_competitor.php" method="post">
	<fieldset>
		<p>
			<b>Navn: <?php echo $this->event_competitor->get_competitor()->get_firstname() . ' ' . $this->event_competitor->get_competitor()->get_lastname(); ?></b>
		</p>

		<p>
			<b>Adresse: <?php echo $this->event_competitor->get_competitor()->get_address(); ?></b>
		</p>
    
		<p>
			<b>Postnr: <?php echo $this->event_competitor->get_competitor()->get_postal_code(); ?> Sted: <?php echo $this->event_competitor->get_competitor()->get_city(); ?></b>
		</p>
    
		<p>
			<b>Telefonnummer: <?php echo $this->event_competitor->get_competitor()->get_phone(); ?></b>
		</p>
    
		<p>
			<b>E-postadresse: <?php echo $this->event_competitor->get_competitor()->get_email(); ?></b>
		</p>

		<p>
			<b>Nasjonalitet: <?php echo $this->event_competitor->get_competitor()->get_nationality(); ?></b>
		</p>

		<p>
			<b>Øvelse: <?php echo $this->event_competitor->get_event()->get_name() . ', ' . date( "d.m.Y \k\l H:i", strtotime( $this->event_competitor->get_event()->get_time() ) ); ?></b>
		</p>

		<p>
			<b>Registreringen er utført: <?php echo date( "d.m.Y \k\l H:i", $this->event_competitor->get_timestamp() ); ?></b>
		</p>
	</fieldset>

	<div align="center"><input type="button" name="cancel" value="Avbryt" onclick="window.location='index.php?msg=Registreringen ble avbrutt.'" /> 
	<input type="button" name="previous" value="Tilbake" onclick="history.go(-1);" /> 
	<input type="submit" name="submit" value="Bekreft registrering" /> </div>

	<input type="hidden" name="firstname" value="<?php echo $this->event_competitor->get_competitor()->get_firstname(); ?>" />
	<input type="hidden" name="lastname" value="<?php echo $this->event_competitor->get_competitor()->get_lastname(); ?>" />
	<input type="hidden" name="address" value="<?php echo $this->event_competitor->get_competitor()->get_address(); ?>" />
	<input type="hidden" name="postal_code" value="<?php echo $this->event_competitor->get_competitor()->get_postal_code(); ?>" />
	<input type="hidden" name="city" value="<?php echo $this->event_competitor->get_competitor()->get_city(); ?>" />
	<input type="hidden" name="phone" value="<?php echo $this->event_competitor->get_competitor()->get_phone(); ?>" />
	<input type="hidden" name="email" value="<?php echo $this->event_competitor->get_competitor()->get_email(); ?>" />
	<input type="hidden" name="nationality" value="<?php echo $this->event_competitor->get_competitor()->get_nationality(); ?>" />
	<input type="hidden" name="event_ID" value="<?php echo $this->event_competitor->get_event()->get_event_ID(); ?>" />
	<input type="hidden" name="timestamp" value="<?php echo $this->event_competitor->get_timestamp(); ?>" />

	<input type="hidden" name="confirmed" value="TRUE" />
