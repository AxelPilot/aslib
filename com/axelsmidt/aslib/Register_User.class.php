<?php

namespace com\axelsmidt\aslib;

/**
 *
 */
class Register_User extends Handler {

    protected $user;
    protected $page_subtitle;
    protected $validation_exceptions;

    /**
     *
     */
    protected function initial_action() {
        $this->set_page_subtitle('Create user account');
        $this->print_page_subtitle();
        new Register_User_Form();
    }

    /**
     *
     */
    protected function submitted_action() {
        $this->set_page_subtitle('Create user account');
        $this->print_page_subtitle();

        // Save the user to the database.
        try {
            $user = new User(NULL, $_POST['email'], $_POST['password1'], $_POST['password2'], $_POST['lastname'],
                    $_POST['firstname'], $_POST['address'], $_POST['postal_code'], $_POST['city'], $_POST['phone']);

            $user->save_to_db();

            $title = 'Welcome!';
            $message = 'Thank you for registering!' . "\r\n";
            $message .= 'Welcome as a new user of ' . $page_title . '.' . "\r\n";
            $notification = new Notification(NULL, $user, $title, $message);

            if ($_POST['admin'] == 'apply_for_admin') {
                $user->apply_for_admin();
            }

            // If successful, redirect to index.php and display a confirmation message.
            redirect('index.php?msg=Thank you for registering! A confirmation is sent to your registered email address.<br />Please click the link in the email to activate your account.');
            exit(); // Quit the script.
        } catch (AsDbErrorException $e) {
            echo '<div class="Error">' . $e->getAsMessage() . '</div>';
            echo '<p><div class="Error">Please try again later.</div></p>';
        } catch (AsDbException $e) {
            echo '<div class="Error">' . $e->getAsMessage() . '</div>';
            echo '<p><div class="Error">Please try again.</div></p>';
        } catch (AsFormValidationException $e) {
            $this->validation_exceptions = $e->getAsMessage();
        }

        new Register_User_Form();
    }

    /**
     *
     */
    protected function confirmed_action() {
        
    }

}

// End of class Register_User.
?>
