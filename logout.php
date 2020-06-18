<?php
include( './includes/head.inc.php' );
include(  "includes/header.inc.php" );
echo '<h1>Logg ut</h1>';
new Logout();
redirect( 'index.php?msg=Du er nå logget ut.' );
exit();
include( "includes/footer.inc.php" );
?>
