<?php

// ************************************************************************
/**
 *
 */
abstract class Handler
{
	
// ************************************************************************
/**
 *
 */
	public function __construct()
	{
		// Confirmed.
		if ( isset( $_POST[ 'confirmed' ] ) )
		{
			$this->confirmed_action();
		}

		// Submitted.
		elseif ( isset( $_POST[ 'submitted' ] ) )
		{
			$this->submitted_action();
		}
		else
		{ // Not yet submitted.
			$this->initial_action();
		}
	}

// ************************************************************************
/**
 *
 */
	abstract protected function initial_action();

// ************************************************************************
/**
 *
 */
	abstract protected function submitted_action();

// ************************************************************************
/**
 *
 */
	abstract protected function confirmed_action();

// ************************************************************************
/**
 * Returns the subtitle of the current page.
 */
	protected function get_page_subtitle()
	{
		return isset( $this->page_subtitle ) ? $this->page_subtitle : NULL;
	}

// ************************************************************************
/**
 * Sets the subtitle of the current page.
 */
	protected function set_page_subtitle( $subtitle )
	{
		$this->page_subtitle = $subtitle;
	}

// ************************************************************************
/**
 * Prints the subtitle onto the current page.
 */
	protected function print_page_subtitle()
	{
		echo '<h1>' . $this->page_subtitle . '</h1>';
	}

// ************************************************************************

} // End of class Handler.

// ************************************************************************

?>
