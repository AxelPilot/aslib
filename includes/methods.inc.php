<?php

// ************************************************************************
/**
 * Custom function for execution on shutdown.
 */
function shutdown()
{
	global $errorlogpath;
	$error = error_get_last();
	if( $error[ 'type' ] === E_ERROR )
	{
		$msg = $error[ 'message' ] . "\r\n";
		error_log( $msg, 3, $errorlogpath );
		redirect( 'index.php?error=' . $system_error_try_again );
	}	
}

// ************************************************************************

register_shutdown_function( 'shutdown' );

// ************************************************************************
/**
 * Custom error handler.
 */
function myErrorHandler( $errno, $errstr, $errfile, $errline )
{
	global $errorlogpath;
	$error = date( 'd.m.Y H:i' ) . ' ';
	$error .= $errfile . ' ';
	$error .= $errline . ' ';
	$error .= $errno . ' ';
	$error .= $errstr . "\r\n";
	
	error_log( $error, 3, $errorlogpath );
}

// ************************************************************************

set_error_handler( 'myErrorHandler', E_ALL );

// ************************************************************************
/**
 * Custom class autoloader.
 */
function custom_autoloader( $class )
{
	require_once './classes/' . $class . '.class.php';
}

// ************************************************************************

spl_autoload_register( 'custom_autoloader' );

// ************************************************************************
/**
 *
 */
function getDir()
{
	// Start defining the URL.
	$dir = dirname( $_SERVER[ 'PHP_SELF' ] );
			
	// Check for a trailing slash.
	if ( ( substr( $dir, -1 ) == '/' ) || ( substr( $dir, -1 ) == '\\' ) )
	{
		$dir = substr( $dir, 0, -1 ); // Chop off the slash.
	}
	
	return $dir;
}

// ************************************************************************
/**
 * Returns the filename and URL parameters from the current URL.
 */
function get_filename_with_url_params()
{
	return substr( $_SERVER[ 'REQUEST_URI' ], strlen( getDir() ) + 1 );
}

// ************************************************************************
/**
 * Returns true if the user is logged in.
 * Redirects the user to the login page if the user is not logged in.
 */
function is_loggedIn( $ajax = false )
{
	// If no user_ID or first_name variable exists, redirect the user.
	if ( !isset( $_SESSION[ 'user_ID' ] ) || !isset( $_SESSION[ 'firstname' ] ) 
		|| !isset( $_SESSION[ 'lastname' ] ) || !isset( $_SESSION[ 'admin' ] ) )
	{
		if( !$ajax )
		{
			new Login( get_filename_with_url_params() );
		}
		return false;
	}
	else
	{
		return true;
	}
}

// ************************************************************************
/**
 *
 */
function getBaseUrl()
{
	// Start defining the URL.
	$url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . dirname( $_SERVER[ 'PHP_SELF' ] );
			
	// Check for a trailing slash.
	if ( ( substr( $url, -1 ) == '/' ) || ( substr( $url, -1 ) == '\\' ) )
	{
		$url = substr( $url, 0, -1 ); // Chop off the slash.
	}
	
	return $url;
}

// ************************************************************************
/**
 * Redirects the user to a web page with the same relative path as the 
 * current web page.
 *
 * @param string $filename The filename of the web page to redirect to.
 */
function redirect( $filename, $full_url = false, $onload_func = NULL, $add_params = NULL )
{
	$url = ( !$full_url || !isset( $full_url ) ? getBaseUrl() . "/" : "" ) . $filename;
	$url .= ( isset( $onload_func ) ? ( strpos( $url, '?' ) === false ? "?" : "&" ) . "onload=" . $onload_func : "" );
	$url .= ( isset( $add_params ) ? ( strpos( $url, '?' ) === false ? "?" : "&" ) . $add_params : "" );
	ob_end_clean(); // Delete the buffer.
	header( "Location: " . $url );
}

// ************************************************************************

?>