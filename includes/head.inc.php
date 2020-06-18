<?php

// ************************************************************************

ob_start(); // Start output buffering.

// Starts the session, and includes config-wp-oblig3.inc.php and methods.inc.php.
require_once './includes/ajax_header.inc.php';

// ************************************************************************

?>
<!DOCTYPE HTML>
<head>
<meta charset="utf-8">
<title><?php echo $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="./includes/<?php echo $stylesheet; ?>" media="screen" />
<link href='./menus/202/menu202id13_style.css' rel='stylesheet' type='text/css'>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript" src="./includes/skivm.js" charset="utf-8"></script>
