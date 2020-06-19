<?php

use com\axelsmidt\aslib as aslib;

include( './includes/head.inc.php' );
?>
<script type="text/javascript" src="./includes/login.js" charset="utf-8"></script>
<?php

include( './includes/header.inc.php' );
new aslib\Login();
include( './includes/footer.inc.php' );
?>
