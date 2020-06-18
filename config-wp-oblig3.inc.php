<?php
// ************************************************************************
// ************************************************************************
// No error messages will be shown to the user.

// error_reporting( E_ALL );
error_reporting( 0 );

// ************************************************************************
// ************************************************************************

// ** MySQL settings ** //
define( 'DB_NAME', 'ski_vm' );            // The name of the database
define( 'DB_USER', 'root' );            // Your MySQL Username
define( 'DB_PASSWORD', '' );               // Your MySQL Password
define( 'DB_HOST', '' ); // Your MySQL Hostmane
define( 'DB_CHARSET', 'utf8' );            // The character set of the database
define( 'DB_COLLATE', '' );

// ************************************************************************
// ************************************************************************
// You can have multiple installations in one database if you give each 
// a unique prefix.

// Only numbers, letters, and underscores please!
define( 'TABLE_PREFIX', 'wp2_' );

// ************************************************************************
// ************************************************************************
// Name of the site's css style sheet.

$stylesheet = "layout.css";

// ************************************************************************
// ************************************************************************
// The main page title to be used unless it is explicitly set within each page.

if ( !isset( $page_title ) )
{
	$page_title = 'Ski VM';
}

// ************************************************************************
// ************************************************************************
// Relative path of the error log file.

if ( !isset( $errorlogpath ) )
{
	$errorlogpath = "./error/errorlog.txt";
}

?>
