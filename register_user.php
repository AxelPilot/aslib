<?php

use com\axelsmidt\aslib as aslib;

include( './includes/head.inc.php' );
?>
<script type="text/javascript" src="./includes/register_user.js" charset="utf-8"></script>
<?php

include( './includes/header.inc.php' );
new aslib\Register_User();
include( './includes/footer.inc.php' );
?>
