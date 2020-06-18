<form action="apply_for_admin.php" method="post">
<div style="display:inline-block;">
	<fieldset style="display:inline;">
        <div class="FormField NoFloat">
	    	<b>Ønsker du å søke om å bli administrator?</b><br />
		</div>
	</fieldset>

    <div align="center">
    	<input type="button" name="no" onclick="window.location = 'index.php'" value="Nei" />
    	<input type="submit" name="submit" value="Ja" />
	</div>
</div>

   	<input type="hidden" name="submitted" value="TRUE" />
</form>
