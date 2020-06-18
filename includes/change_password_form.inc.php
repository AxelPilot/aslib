<form action="./change_password.php" method="post">
	<fieldset>
    
        <div class="FormField NoFloat">
			<b>Gammelt passord:</b><br />
			<div class="FloatLeft" style="margin-right:8px";>
	            <input type="password" id="old_password" name="password" size="30" maxlength="20" style="width:211px;<?php
					if( isset( $this->validation_exceptions[ 'password' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" /><br />
			</div>

			<div id="old_password_exception" class="validation_exception NoFloat">
				<?php 
					if( isset( $this->validation_exceptions[ 'password' ] ) )
					{
						echo $this->validation_exceptions[ 'password' ];
					}
				?>
			</div>
		</div>

        <div class="FormField NoFloat">
			<b>Nytt passord:</b> <small>Kun bokstaver og tall. Må være 4-20 tegn.</small><br />
			<div class="FloatLeft" style="margin-right:8px";>
	            <input type="password" id="new_password" name="password1" size="30" maxlength="20" style="width:211px;<?php
					if( isset( $this->validation_exceptions[ 'new_password' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" /><br />
			</div>

			<div id="new_password_exception" class="validation_exception NoFloat">
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
	            <input type="password" id="confirmed_password" name="password2" size="30" maxlength="20" style="width:211px;<?php
					if( isset( $this->validation_exceptions[ 'confirmed_password' ] ) )
					{
						echo ' border-color: #F00;';
					}
				?>" /><br />
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

	</fieldset>
    
    <div align="center"><input type="submit" name="submit" value="Endre passord" /></div>
    <input type="hidden" name="submitted" value="TRUE" />
</form>
