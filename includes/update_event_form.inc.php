<form action="./update_event.php" name="update_event_form" method="post">
	<fieldset>
<?php
	try
	{
		$event_list = new Event_List();
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
<br />
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
				?>" <?php
					if( !isset( $_POST[ 'event_ID' ] ) || (isset( $_POST[ 'event_ID' ] ) && ( $_POST[ 'event_ID' ] < 0 ) ) )
					{
						echo 'disabled';
					}
				?>/>
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
				?>" <?php
					if( !isset( $_POST[ 'event_ID' ] ) || (isset( $_POST[ 'event_ID' ] ) && ( $_POST[ 'event_ID' ] < 0 ) ) )
					{
						echo 'disabled';
					}
				?>/>
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

	<div align="center"><input type="button" name="cancel" value="Avbryt" onclick="window.location='index.php?msg=Operasjonen ble avbrutt.'" /> 
	<input type="submit" name="submit" value="Lagre endringer" /></div>
	<div align="center"><div class="txtbg"><small>Du kan bekrefte utfylte data i neste steg.</small></div></div>

    <input type="hidden" name="submitted" value="true" />
</form>
