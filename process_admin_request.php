<?php
include( './includes/head.inc.php' );
include( './includes/header.inc.php' );
if( is_loggedIn() )
{
	new Process_Admin_Request();
}
include( './includes/footer.inc.php' );

?>
