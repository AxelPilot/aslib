<form action="./register_event.php" method="post">
	<fieldset>
    
		<div class="FormField NoFloat">
			<b>Tidspunkt:</b><br />
			<div class="FloatLeft" style="margin-right:12px";>
				<input type="text" id="event_time" name="event_time" id="event_time" size="19" maxlength="19"<?php
					if ( isset( $_POST[ 'event_time' ] ) )
					{
						echo ' value="' . $_POST[ 'event_time' ] . '"';
					}
				?> style="width:150px;<?php
					if( isset( $this->validation_exceptions[ 'event_time' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
				<small>ÅÅÅÅ-MM-DD TT:MM</small>
			</div>

			<div id="event_time_exception" class="validation_exception NoFloat">
				<?php 
					if( isset( $this->validation_exceptions ) && isset( $this->validation_exceptions[ 'event_time' ] ) )
					{
						echo $this->validation_exceptions[ 'event_time' ];
					}
				?>
			</div>
		</div>

		<div class="FormField NoFloat">
			<b>Navn på øvelse:</b><br />
			<div class="FloatLeft" style="margin-right:12px";>
				<input type="text" id="event_name" name="event_name" id="event_name" size="45" maxlength="30"<?php
					if ( isset( $_POST[ 'event_name' ] ) )
					{
						echo ' value="' . $_POST[ 'event_name' ] . '"';
					}
				?> style="width:300px;<?php
					if( isset( $this->validation_exceptions[ 'event_name' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
			</div>

			<div id="event_name_exception" class="validation_exception NoFloat">
				<?php 
					if( isset( $this->validation_exceptions ) && isset( $this->validation_exceptions[ 'event_name' ] ) )
					{
						echo $this->validation_exceptions[ 'event_name' ];
					}
				?>
			</div>
		</div>

	</fieldset>
    
	<div align="center"><input type="button" name="cancel" value="Avbryt" onclick="window.location='index.php?msg=Registreringen ble avbrutt.'" /> 
	<input type="submit" name="submit" value="Registrer øvelse" /></div>
	<div align="center"><div class="txtbg"><small>Du kan bekrefte utfylte data i neste steg.</small></div></div>

    <input type="hidden" name="submitted" value="true" />
</form>
