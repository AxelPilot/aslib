<?php

// ************************************************************************
/**
 *
 */
class Event_List extends GUI
{
	
// ************************************************************************

	const FUTURE_ONLY = true;
	const ALL_EVENTS = false;
	
// ************************************************************************

	protected $events = NULL;
	protected $caption = NULL;
	protected $onChange = NULL;
	protected $exception = NULL;
	protected $show_exceptions = false;

// ************************************************************************
/**
 *
 */
	public function __construct( $span = self::ALL_EVENTS, 
                                 $float = parent::NO_FLOAT )
	{
		$this->events = $this->get_events( $span );
		parent::__construct( $float );
	}
	
// ************************************************************************
/**
 *
 */
	public function add_caption( $caption )
	{
		if( isset( $caption ) )
		{
			$this->caption = $caption;
		}
	}

// ************************************************************************
/**
 *
 */
	public function set_onChange( $action )
	{
		$this->onChange = $action;
	}

// ************************************************************************
/**
 *
 */
	public function set_exception( $exception )
	{
		$this->exception = $exception;
		$this->show_exceptions = true;
	}

// ************************************************************************
/**
 *
 */
	public function show()
	{
		?>
		<div class="<?php echo $this->float; ?>">
		<?php
		echo isset( $this->caption ) ? '	<b>' . trim( $this->caption ) . '</b><br />' . "\r\n" : '';
		if( $this->show_exceptions )
		{
			?>
			<div class="FloatLeft" style="margin-right:8px";>
			<?php
		}
		?>
		<select size="1" id="event_selector" name="event_ID"<?php
		echo isset( $this->onChange ) ? ' onchange="' . $this->onChange . '"' : "";

		if( isset( $this->exception ) )
		{
			?> style="border-color: #F00;"<?php
		}
		?>>

		<option value="-1">Select...</option>
		<?php
		if( count( $this->events ) > 0 )
		{
			foreach( $this->events as $r )
			{
				?>
				<option value="<?php echo $r[ 'event_ID' ]; ?>"
				<?php
				if( isset( $_POST[ 'event_ID' ] ) && ( $r[ 'event_ID' ] == $_POST[ 'event_ID' ] ) )
				{
					?> selected="selected"<?php
				}

				?>><?php echo $r[ 'name' ] . ' - ' . date( "d.m.Y \k\l H:i", $r[ 'eventtime' ] ); ?></option>
				<?php
			}
		}
		?>
		</select>
		<?php
		if( $this->show_exceptions )
		{
			?>
			</div>
			<?php
		}
		?>

		<?php 
		if( $this->show_exceptions )
		{
			?>
			<div id="event_selector_exception" class="validation_exception NoFloat">
			<?php
			echo isset( $this->exception ) ? $this->exception : "";
			?>
			</div>
			<?php
		}
		?>
		</div>
		<?php
	}

// ************************************************************************
// ************************************************************************
/**
 *
 */
	protected function get_events( $future_only = self::ALL_EVENTS )
	{
		if( $mysqli = AsMySQLi::connect2db( $technical_error ) )
		{
			$query = "
			SELECT event_ID, UNIX_TIMESTAMP(time) AS eventtime, name
			FROM " . TABLE_PREFIX . "event";
			
			$query .= $future_only ? "
			WHERE
			(UNIX_TIMESTAMP(time) - UNIX_TIMESTAMP(NOW())) > 0 " : " ";
			
			$query .= "
			ORDER BY time";
			
			if( $result = $mysqli->query( $query ) )
			{
				$events = array();
			
				if( $result->num_rows > 0 )
				{
					while( $row = $result->fetch_assoc() )
					{
						$events[] = $row;
					}
				}
				else
				{
					$events = false;
				}
				$result->free();
			}
			
			$mysqli->close();
			return $events;
		}
	}

// ************************************************************************

} // End of class Event_List.

// ************************************************************************

?>