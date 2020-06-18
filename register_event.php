<?php
include( './includes/head.inc.php' );
?>
<script type="text/javascript" src="./includes/register_event.js" charset="utf-8"></script>
<?php
include( './includes/header.inc.php' );

if( is_loggedIn() )
{
	new Register_Event();
}

include( './includes/footer.inc.php' );
?>
