<?php

namespace com\axelsmidt\aslib;

/**
 *
 */
class Admin_Request {

    protected $applicant;
    protected $activation_code;
    protected $admins;

    const APPROVED = true;
    const DENIED = false;

    /**
     *
     */
    public function __construct($applicant, $throw_exceptions = AsException::THROW_ALL) {
        $this->applicant = $applicant;
    }

    /**
     *
     */
    public function apply($sender, $throw_exceptions = AsException::THROW_ALL) {
        $ok = false;
        if ($this->generate_activation_code_and_save_to_db($throw_exceptions) && ( $this->admins = $this->get_admins() )) {
            $title = 'Administrator Request';
            $message = $this->applicant->get_firstname() . ' ' . $this->applicant->get_lastname();
            $message .= ' is applying to become an administrator.';
            $url = getBaseUrl() . '/process_admin_request.php';
            $params = 'x=' . $this->applicant->get_user_ID() . '&y=' . $this->activation_code;

            $notification = new Notification(NULL, $this->admins, $title, $message, $url, $params, $throw_exceptions);
            $notification->set_params($notification->get_params() . "&nid=" . $notification->get_notification_ID());
            $notification->send_by_email();

            $title = 'Your administrator application is received';
            $message = 'Your administrator application is received.' . "\r\n";
            $message .= 'You will be notified by email when the application is processed.' . "\r\n\r\n";
            $message .= 'Sincerely' . "\r\n";
            $message .= $sender . "\r\n";
            $notification = new Notification(NULL, $this->applicant, $title, $message, '#', NULL, $throw_exceptions);
            $notification->send_by_email();

            $ok = true;
        }
        return $ok;
    }

    /**
     *
     */
    public function approve($activation_code, $approved = self::APPROVED, $throw_exceptions = AsException::THROW_ALL) {
        $ok = false;
        if ($mysqli = AsMySQLi::connect2db("We apologize, but a technical error has occured.", $throw_exceptions)) {
            $query = "
			UPDATE " . TABLE_PREFIX . "user
			SET activation_admin = NULL, " . ( $approved == self::APPROVED ? "admin = 'Y'" : "admin = 'N'" ) . "
			WHERE
			user_ID = " . $this->applicant->get_user_ID() . " 
			AND 
			activation_admin = '" . $mysqli->escape_data($activation_code) . "'
			LIMIT 1";

            if ($mysqli->query($query) && ( $mysqli->affected_rows == 1 )) {
                $ok = true;
            } elseif ($throw_exceptions >= AsException::THROW_DB_ERROR) {
                $mysqli->close();
                throw new AsDbErrorException("We apologize, but a technical error has occured.");
            }
            $mysqli->close();
        }

        if ($ok) {
            $notification_title = $approved ?
                    "Administrator request approved" :
                    "Administrator request denied";

            $notification_message = $approved ?
                    "Your Administrator request is approved." :
                    "Your Administrator request is denied.";

            $notification = new Notification(NULL, $this->applicant, $notification_title,
                    $notification_message, "#", NULL, $throw_exceptions);
            $notification->send_by_email();
        }
        return $ok;
    }

    /**
     *
     */
    protected function generate_activation_code_and_save_to_db($throw_exceptions = AsException::THROW_ALL) {
        $ok = false;
        if (!$this->applicant->is_admin()) {
            $user_ID = $this->applicant->get_user_ID();
            $email = $this->applicant->get_email();
            $this->activation_code = $this->applicant->create_activation_code();

            if ($mysqli = AsMySQLi::connect2db("We apologize, but a technical error has occured.", $throw_exceptions)) {
                $query = "
				UPDATE " . TABLE_PREFIX . "user
				SET activation_admin = '" . $mysqli->escape_data($this->activation_code) . "'
				WHERE ";
                $query .= isset($user_ID) ?
                        "user_ID = " . $user_ID :
                        "UPPER(email) = UPPER('" . $mysqli->escape_data($email) . "')";

                if ($mysqli->query($query) && ( $mysqli->affected_rows == 1 )) {
                    $ok = true;
                } elseif ($throw_exceptions >= AsException::THROW_DB_ERROR) {
                    $mysqli->close();
                    throw new AsDbErrorException("We apologize, but a technical error has occured.");
                }
                $mysqli->close();
            }
        }
        return $ok;
    }

    /**
     *
     */
    protected function get_admins($throw_exceptions = AsException::THROW_ALL) {
        $ok = false;
        $admins = array();
        if ($mysqli = AsMySQLi::connect2db("We apologize, but a technical error has occured.", $throw_exceptions)) {
            $query = "
			SELECT user_ID
			FROM " . TABLE_PREFIX . "user
			WHERE
			admin = 'Y'
			ORDER BY lastname, firstname";

            if ($result = $mysqli->query($query)) {

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $admins[] = new User($row['user_ID']);
                    }
                    $ok = true;
                }
                $result->close();
            } else {
                if ($throw_exceptions >= AsException::THROW_DB_ERROR) {
                    $mysqli->close();
                    throw new AsDbErrorException("We apologize, but a technical error has occured.");
                }
            }
        }
        return $ok ? $admins : false;
    }

}

// End of class Admin_Request.
?>
