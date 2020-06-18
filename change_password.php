<?php
include( './includes/head.inc.php' );
?>
<script type="text/javascript" src="./includes/change_password.js" charset="utf-8"></script>
<?php
include( './includes/header.inc.php' );
if( is_loggedIn() )
{
	new Change_Password();
}
include( './includes/footer.inc.php' );
?>
