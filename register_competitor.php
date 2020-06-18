<?php
include( './includes/head.inc.php' );
?>
<script type="text/javascript" src="./includes/register_competitor.js" charset="utf-8"></script>
<?php
include( './includes/header.inc.php' );

if( is_loggedIn() )
{
	new Register_Competitor();
}

include( './includes/footer.inc.php' );
?>