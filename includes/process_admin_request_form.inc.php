<form id="approve_admin_form" action="process_admin_request.php" method="post">
<div style="display:inline-block;">
	<fieldset style="display:inline;">
        <div class="FormField NoFloat">
	    	<b>Brukeren <?php echo $this->user->get_firstname() . " " . $this->user->get_lastname() ?> ønsker å få innvilget status som administrator.</b><br />
		</div>
	</fieldset>

    <div align="center">
    	<input type="button" name="deny" onclick="deny_admin()" value="Avslå" />
    	<input type="button" name="approve" onclick="approve_admin()" value="Godkjenn" />
	</div>
</div>

   	<input type="hidden" id="admin_approved_status" name="approved_status" value="<?php echo $this->approved_status; ?>" />
   	<input type="hidden" name="user_ID" value="<?php echo $this->user->get_user_ID(); ?>" />
   	<input type="hidden" name="admin_activation_code" value="<?php echo $this->admin_activation_code; ?>" />
   	<input type="hidden" name="nid" value="<?php echo $this->notification_ID; ?>" />
   	<input type="hidden" name="submitted" value="TRUE" />
</form>
