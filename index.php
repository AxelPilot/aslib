<?php
include( './includes/strings.en.inc.php' );
include( './includes/head.inc.php' );
include( './includes/header.inc.php' );
?>

<h2>Select an Exercise to View Info about Contestants and Ticket Sales:</h2>

<?php
include( './includes/index_form.inc.php' );

if( $msg = filter_input( INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS ) )
{
	echo '<div class="txtbg2 IndexMessage" style="margin-left:10px;">' . $msg 
        . '</div>';
}

if( $error = filter_input( INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS ) )
{
	echo '<div class="txtbg2 IndexError" style="margin-left:10px;">' . $error
        . '</div>';
}

include( './includes/footer.inc.php' );
?>