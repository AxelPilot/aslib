<?php

// ************************************************************************

class Customer extends User
{

// ************************************************************************
/**
 *
 */
	public function __construct( $user_ID, $throw_exceptions = AsException::THROW_ALL )
	{
		parent::__construct( $user_ID, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $throw_exceptions );
	}
	
// ************************************************************************

} // end of class Customer.

// ************************************************************************

?>
