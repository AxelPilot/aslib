<form action="./register_competitor.php" name="update_competitor_form" method="post">
	<fieldset>
    
<?php
try
{
	$el = new Event_List( Event_List::ALL_EVENTS, GUI::NO_FLOAT );
	$el->add_caption( 'Øvelse:' );
	$el->set_exception( isset( $this->validation_exceptions[ 'event' ] ) ? $this->validation_exceptions[ 'event' ] : NULL );
	$el->show();
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
        <div class="FormField NoFloat">
			<b>E-postadresse:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
				<input type="text" id="email" name="email" size="41" maxlength="40"<?php
				if ( isset( $_POST[ 'email' ] ) )
				{
					echo ' value="' . $_POST[ 'email' ] . '"';
				}
			?> style="width:277px;<?php
					if( isset( $this->validation_exceptions[ 'email' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
			</div>

			<div id="email_exception" class="validation_exception NoFloat">
				<?php 
					if( isset( $this->validation_exceptions[ 'email' ] ) )
					{
						echo $this->validation_exceptions[ 'email' ];
					}
				?>
			</div>
		</div>

		<div class="FormField NoFloat">
			<b>Nasjonalitet:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
				<input type="text" id="nationality" name="nationality" size="41" maxlength="40"<?php
					if( isset( $this->nationality ) )
					{
						echo ' value="' . $this->nationality . '"';
					}
				?> style="width:277px;<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'nationality' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" disabled />
			</div>

			<div id="nationality_exception" class="validation_exception NoFloat">
				<?php 
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'nationality' ] ) )
					{
						echo $this->validation_exceptions[ 'nationality' ];
					}
				?>
			</div>
		</div>

        <div class="FormField FloatLeft">
			<b>Fornavn:</b><br />
				<input type="text" id="firstname" name="firstname" size="16"  maxlength="30"<?php 
					if ( isset( $this->firstname ) )
					{
						echo ' value="' . $this->firstname . '"';
					}
				?> style="width:127px;<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'firstname' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" disabled />
		</div>

        <div class="FormField NoFloat">

			<b>Etternavn:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
				<input type="text" id="lastname" name="lastname" size="16" maxlength="30"<?php 
					if ( isset( $this->lastname ) )
					{
						 echo ' value="' . $this->lastname . '"';
					}
				?> style="width:127px;<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'lastname' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" disabled />
			</div>

			<div id="firstname_exception" class="validation_exception FloatLeft" style="margin-right:15px;">
				<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'firstname' ] ) )
					{
						echo $this->validation_exceptions[ 'firstname' ];
					}
				?>
			</div>
	
			<div id="lastname_exception" class="validation_exception NoFloat">
				<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'lastname' ] ) )
					{
						echo $this->validation_exceptions[ 'lastname' ];
					}
				?>
			</div>
		</div>

        <div class="FormField NoFloat">
			<b>Adresse:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
				<input type="text" id="address" name="address" size="41" maxlength="45"<?php
					if ( isset( $this->address ) )
					{
						echo ' value="' . $this->address . '"';
					}
				?> style="width:277px;<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'address' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" disabled />
			</div>

			<div id="address_exception" class="validation_exception NoFloat">
				<?php 
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'address' ] ) )
					{
						echo $this->validation_exceptions[ 'address' ];
					}
				?>
			</div>
		</div>

        <div class="FormField FloatLeft">
			<b>Postnr:</b><br />
				<input type="text" id="postal_code" name="postal_code" size="4" maxlength="4"<?php
					if ( isset( $this->postal_code ) )
					{
						echo ' value="' . $this->postal_code . '"';
					}
				?> style="width:50px;<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'postal_code' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" disabled />
		</div>

        <div class="FormField NoFloat">
			<b>By:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
				<input type="text" id="city" name="city" size="28" maxlength="30"<?php
					if ( isset( $this->city ) )
					{
						echo ' value="' . $this->city . '"';
					}
				?> style="width:204px;<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'city' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" disabled />
			</div>

			<div id="postal_code_exception" class="validation_exception FloatLeft" style="margin-right:15px;">
				<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'postal_code' ] ) )
					{
						echo $this->validation_exceptions[ 'postal_code' ];
					}
				?>
			</div>
	
			<div id="city_exception" class="validation_exception NoFloat">
				<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'city' ] ) )
					{
						echo $this->validation_exceptions[ 'city' ];
					}
				?>
			</div>
		</div>

        <div class="FormField NoFloat">
			<b>Telefon:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
				<input type="text" id="phone" name="phone" size="41" maxlength="20"<?php
					if ( isset( $this->phone ) )
					{
						echo ' value="' . $this->phone . '"';
					}
				?> style="width:277px;<?php
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'phone' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" disabled />
			</div>

			<div id="phone_exception" class="validation_exception NoFloat">
				<?php 
					if( !isset( $this->validation_exceptions[ 'email' ] ) && isset( $this->validation_exceptions[ 'phone' ] ) )
					{
						echo $this->validation_exceptions[ 'phone' ];
					}
				?>
			</div>
		</div>

	</fieldset>
    
	<div align="center"><input type="button" name="cancel" value="Avbryt" onclick="window.location='index.php?msg=Registreringen ble avbrutt.'" /> 
	<input type="submit" name="submit" value="Utfør registrering" /></div>
	<div align="center"><div class="txtbg"><small>Du kan bekrefte registreringen i neste steg.</small></div></div>

    <input type="hidden" name="submitted" value="true" />
</form>
