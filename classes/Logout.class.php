<?php
class Logout
{
	public function __construct()
	{
		if( is_loggedIn() )
		{ 
			// Log out the user.
			$_SESSION = array(); // Destroy the session variables.
			session_destroy(); // Destroy the session itself.
			setcookie( session_name(), '', time() - 300, '/', '', 0 ); // Destroy the cookie associated with the session.
		}
	}
}
?>