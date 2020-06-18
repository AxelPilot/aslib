<?php
include( './includes/head.inc.php' );
include( './includes/header.inc.php' );
if( is_loggedIn() )
{
	new Apply_For_Admin();
}
include( './includes/footer.inc.php' );
?>
