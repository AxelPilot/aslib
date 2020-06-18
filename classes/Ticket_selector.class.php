<?php

// ************************************************************************
/**
 *
 */
class Ticket_selector extends GUI
{
	
// ************************************************************************

	protected $caption = NULL;
	
// ************************************************************************
/**
 *
 */
	public function __construct( $caption = 'Antall billetter:', $float = parent::NO_FLOAT )
	{
		$this->caption = $caption;
		parent::__construct( $float );
	}

// ************************************************************************
/**
 *
 */
	public function show()
	{
		echo '<div class="' . $this->float . '">';
		echo $this->caption != "" ? '	<b>' . trim( $this->caption ) . '</b><br />' . "\r\n" : '';
		echo '<select size="1" name="ticket_count">' . "\r\n";
		for( $n = 1; $n <= 30; $n++ )
		{
			echo '<option';
			if( isset( $_POST[ 'ticket_count' ] ) && ( $n == $_POST[ 'ticket_count' ] ) )
			{
				echo ' selected="selected"';
			}

			echo '>' . $n . '</option>' . "\r\n";
		}
		echo '</select>' . "\r\n";
		echo '</div>' . "\r\n\r\n";
	}

// ************************************************************************

} // Enf of class Ticket_selector.
	
// ************************************************************************

?>
