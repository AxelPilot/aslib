<?php

// ************************************************************************

class Notification_List extends GUI
{

// ************************************************************************

	const UNREAD_ONLY = 1;
	const ALL = 0;
	const AJAX = true;
	
// ************************************************************************

	protected $notifications = NULL;
	protected $user_ID;
	protected $ajax;

// ************************************************************************
/**
 *
 */
	public function __construct( $user_ID, $filter = self::UNREAD_ONLY, $ajax = false )
	{
		$this->ajax = $ajax;
		$this->user_ID = $user_ID;
		$this->notifications = $this->get_notifications( $filter, $ajax );
	}

// ************************************************************************
/**
 *
 */
	public function show()
	{
		if( isset( $this->notifications ) && count( $this->notifications > 0 ) )
		{
			?>
			<div id="notifications">
			<div style="margin-bottom:10px;"></div>
			<div id="notification_title"><h1 style="margin-bottom:15px;">Uleste beskjeder:</h1></div>
			<div id="notifications_body">
			
			<?php
			foreach( $this->notifications as $notification )
			{
				?>
				<a class="NotificationLink" id="notification_<?php echo $notification->get_notification_ID(); 
				?>" onclick="notification_open_and_mark_as_read('<?php echo $notification->get_notification_ID(); ?>')"	href="#"><?php 
				echo $notification->get_title(); ?></a>
				<?php
			}
			?>
			</div>
			</div>
			
			<div id="notification_count" style="display: none;">
			<?php
			echo count( $this->notifications );
			?>
			</div>
			<?php
		}
		else
		{
			echo '<div id="notifications">' . "\r\n";
			echo '<div id="notification_title"><h3>No unread messages.</h3></div>' . "\r\n";
			echo '</div>' . "\r\n";
			
			echo '<div id="notification_count" style="display: none;">';
			echo count( $this->notifications );
			echo '</div>';
		}
	}

// ************************************************************************
/**
 *
 */
	public function get_as_json()
	{
		if( $this->ajax === self::AJAX )
		{
			return json_encode( $this->notifications );
		}
	}

// ************************************************************************
/**
 *
 */
	protected function get_notifications( $filter = self::UNREAD_ONLY, $ajax = false )
	{
		if( $mysqli = AsMySQLi::connect2db( $technical_error ) )
		{
			$query = "
			SELECT n.notification_ID, n.title, n.message, n.url, n.params, n.registration_date
			FROM " . TABLE_PREFIX . "notification n
			INNER JOIN " . TABLE_PREFIX . "user_has_notification un
			ON n.notification_ID = un.notification_ID
			WHERE ";
			
			$query .= $filter == self::UNREAD_ONLY ? "
			opened_time IS NULL
			AND" : " ";
			
			$query .= "
			un.user_ID = '" . $this->user_ID . "'
			ORDER BY registration_date";
			
			$notifications = array();

			if( $result = $mysqli->query( $query ) )
			{
				if( $result->num_rows > 0 )
				{
					while( $row = $result->fetch_assoc() )
					{
						$notifications[] = $ajax === self::AJAX ? $row : new Notification( $row[ 'notification_ID' ] );
					}
				}
				else
				{
					$notifications = NULL;
				}
				$result->close();
			}
			
			$mysqli->close();
			return $notifications;
		}
	}

// ************************************************************************

} // End of class Notification_List.

// ************************************************************************

?>
