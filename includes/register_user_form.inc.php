<form action="./register_user.php" method="post">
	<fieldset>

        <div class="FormField FloatLeft">
			<b>Fornavn:</b><br />
				<input type="text" id="firstname" name="firstname" size="16"  maxlength="30"<?php 
					if ( isset( $_POST[ 'firstname' ] ) )
					{
						echo ' value="' . $_POST[ 'firstname' ] . '"';
					}
				?> style="width:127px;<?php
					if( isset( $this->validation_exceptions[ 'firstname' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
		</div>

        <div class="FormField NoFloat">

			<b>Etternavn:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
				<input type="text" id="lastname" name="lastname" size="16" maxlength="30"<?php 
					if ( isset( $_POST[ 'lastname' ] ) )
					{
						 echo ' value="' . $_POST[ 'lastname' ] . '"';
					}
				?> style="width:127px;<?php
					if( isset( $this->validation_exceptions[ 'lastname' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
			</div>

			<div id="firstname_exception" class="validation_exception FloatLeft" style="margin-right:15px;">
				<?php
					if( isset( $this->validation_exceptions[ 'firstname' ] ) )
					{
						echo $this->validation_exceptions[ 'firstname' ];
					}
				?>
			</div>
	
			<div id="lastname_exception" class="validation_exception NoFloat">
				<?php
					if( isset( $this->validation_exceptions[ 'lastname' ] ) )
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
					if ( isset( $_POST[ 'address' ] ) )
					{
						echo ' value="' . $_POST[ 'address' ] . '"';
					}
				?> style="width:277px;<?php
					if( isset( $this->validation_exceptions[ 'address' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
			</div>

			<div id="address_exception" class="validation_exception NoFloat">
				<?php 
					if( isset( $this->validation_exceptions ) && isset( $this->validation_exceptions[ 'address' ] ) )
					{
						echo $this->validation_exceptions[ 'address' ];
					}
				?>
			</div>
		</div>

        <div class="FormField FloatLeft">
			<b>Postnr:</b><br />
				<input type="text" id="postal_code" name="postal_code" size="4" maxlength="4"<?php
					if ( isset( $_POST[ 'postal_code' ] ) )
					{
						echo ' value="' . $_POST[ 'postal_code' ] . '"';
					}
				?> style="width:50px;<?php
					if( isset( $this->validation_exceptions[ 'postal_code' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
		</div>

        <div class="FormField NoFloat">
			<b>By:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
				<input type="text" id="city" name="city" size="28" maxlength="30"<?php
					if ( isset( $_POST[ 'city' ] ) )
					{
						echo ' value="' . $_POST[ 'city' ] . '"';
					}
				?> style="width:204px;<?php
					if( isset( $this->validation_exceptions[ 'city' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
			</div>

			<div id="postal_code_exception" class="validation_exception FloatLeft" style="margin-right:15px;">
				<?php
					if( isset( $this->validation_exceptions[ 'postal_code' ] ) )
					{
						echo $this->validation_exceptions[ 'postal_code' ];
					}
				?>
			</div>
	
			<div id="city_exception" class="validation_exception NoFloat">
				<?php
					if( isset( $this->validation_exceptions[ 'city' ] ) )
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
					if ( isset( $_POST[ 'phone' ] ) )
					{
						echo ' value="' . $_POST[ 'phone' ] . '"';
					}
				?> style="width:277px;<?php
					if( isset( $this->validation_exceptions[ 'phone' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
			</div>

			<div id="phone_exception" class="validation_exception NoFloat">
				<?php 
					if( isset( $this->validation_exceptions ) && isset( $this->validation_exceptions[ 'phone' ] ) )
					{
						echo $this->validation_exceptions[ 'phone' ];
					}
				?>
			</div>
		</div>

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
					if( isset( $this->validation_exceptions ) && isset( $this->validation_exceptions[ 'email' ] ) )
					{
						echo $this->validation_exceptions[ 'email' ];
					}
				?>
			</div>
		</div>

        <div class="FormField NoFloat">
			<b>Passord:</b> <small>Kun bokstaver og tall. Må være 4-20 tegn.</small><br />
			<div class="FloatLeft" style="margin-right:8px";>
	            <input type="password" id="password" name="password1" size="41" maxlength="20" style="width:277px;<?php
					if( isset( $this->validation_exceptions[ 'new_password' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" /><br />
			</div>

			<div id="password_exception" class="validation_exception NoFloat">
				<?php 
					if( isset( $this->validation_exceptions[ 'new_password' ] ) )
					{
						echo $this->validation_exceptions[ 'new_password' ];
					}
				?>
			</div>
		</div>

        <div class="FormField NoFloat">
			<b>Bekreft passord:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
	            <input type="password" id="confirmed_password" name="password2" size="41" maxlength="20" style="width:277px;<?php
					if( isset( $this->validation_exceptions[ 'confirmed_password' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" />
			</div>

			<div id="confirmed_password_exception" class="validation_exception NoFloat">
				<?php 
					if( isset( $this->validation_exceptions[ 'confirmed_password' ] ) )
					{
						echo $this->validation_exceptions[ 'confirmed_password' ];
					}
				?>
			</div>
		</div>

        <div class="FormField NoFloat">
			<br /><b>Søk om å bli administrator:</b>
<input type="checkbox" id="admin" name="admin" value="apply_for_admin" 
<?php if ( isset( $_POST[ 'admin' ] ) && ( $_POST[ 'admin' ] == 'apply_for_admin' ) ) echo ' checked="checked"'; ?> />
			<small>(For å kunne administrere øvelser og utøvere).</small>
		</div>

	</fieldset>
    
	<div align="center">
	<input type="button" name="cancel" value="Avbryt" onclick="window.location='index.php?msg=Registreringen ble avbrutt.'" />
	<input type="submit" name="submit" value="Registrer" />
	</div>
    
	<input type="hidden" name="submitted" value="TRUE" />
</form>
