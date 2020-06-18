<?php
include( './includes/head.inc.php' );
?>
<script type="text/javascript" src="./includes/ticket_purchase.js" charset="utf-8"></script>
<?php
include( './includes/header.inc.php' );

if( is_loggedIn() )
{
	new Ticket_Purchase();
}

include( './includes/footer.inc.php' );
?>
