</head>

<body<?php echo isset( $_GET[ 'onload' ] ) ? ' onload="' . $_GET[ 'onload' ] . '"' : ''; ?>>
<div class="container">
<div id="Main">
<div id="Content">

<?php
if ( isset( $page_subtitle ) )
{
	echo '<h1>' . $page_subtitle . '</h1>';
}
?>
<!-- End of Header -->
