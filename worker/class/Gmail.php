<?php

/**
 * This example shows settings to use when sending via Google's Gmail servers.
 */

namespace dumbu\cls {
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
    date_default_timezone_set('Etc/UTC');

    require_once 'libraries/PHPMailer-master/PHPMailerAutoload.php';

    class Gmail {

        protected $mail = NULL;

        public function __construct() {
//Create a new PHPMailer instance
            $this->mail = new \PHPMailer;

//Tell PHPMailer to use SMTP
            $this->mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
            $this->mail->SMTPDebug = 2;

//Ask for HTML-friendly debug output
            $this->mail->Debugoutput = 'html';

//Set the hostname of the mail server
            $this->mail->Host = 'smtp.gmail.com';
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
            $this->mail->Port = 587;

//Set the encryption system to use - ssl (deprecated) or tls
            $this->mail->SMTPSecure = 'tls';

//Whether to use SMTP authentication
            $this->mail->SMTPAuth = true;

//Username to use for SMTP authentication - use full email address for gmail
            $this->mail->Username = "dumbu.system@gmail.com";

//Password to use for SMTP authentication
            $this->mail->Password = "sorvete69@";

//Set who the message is to be sent from
            $this->mail->setFrom('dumbu.system@gmail.com', 'DUMBU');
        }

        public function send_client_login_error($useremail, $username, $instaname, $instapass) {
            //Set an alternative reply-to address
//$mail->addReplyTo('albertord@ic.uff.br', 'First Last');
//Set who the message is to be sent to
            $this->mail->clearAddresses();
            $this->mail->addAddress($useremail, $username);
            $this->mail->clearCCs();
            $this->mail->addCC($GLOBALS['sistem_config']::SYSTEM_EMAIL, $GLOBALS['sistem_config']::SYSTEM_USER_LOGIN);

//Set the subject line
            $this->mail->Subject = 'DUMBU Client Login';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
            $username = urlencode($username);
            $instaname = urlencode($instaname);
            $instapass = urlencode($instapass);
//            $this->mail->msgHTML(file_get_contents("http://localhost/dumbu/worker/resources/emails/login_error.php?username=$username&instaname=$instaname&instapass=$instapass"), dirname(__FILE__));
            //echo "http://" . $_SERVER['SERVER_NAME'] . "<br><br>";
            $this->mail->msgHTML(file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/dumbu/worker/resources/emails/login_error.php?username=$username&instaname=$instaname&instapass=$instapass"), dirname(__FILE__));

//Replace the plain text body with one created manually
            $this->mail->AltBody = 'DUMBU Client Login';

//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
            if (!$this->mail->send()) {
                echo "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                echo "Message sent!";
            }
            $this->mail->smtpClose();
        }

        public function send_client_contact_form($username, $useremail, $usermsg, $usercompany = NULL, $userphone = NULL) {
            //Set an alternative reply-to address
//$mail->addReplyTo('albertord@ic.uff.br', 'First Last');
//Set who the message is to be sent to
            $this->mail->clearAddresses();
            $this->mail->addAddress($GLOBALS['sistem_config']::SYSTEM_EMAIL, $GLOBALS['sistem_config']::SYSTEM_USER_LOGIN);
            $this->mail->clearReplyTos();
            $this->mail->addReplyTo($useremail, $username);

//Set the subject line
            $this->mail->Subject = 'User Contact';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
            $username = urlencode($username);
            $usermsg = urlencode($usermsg);
            $usercompany = urlencode($usercompany);
            $userphone = urlencode($userphone);
//            $this->mail->msgHTML(file_get_contents("http://localhost/dumbu/worker/resources/emails/login_error.php?username=$username&instaname=$instaname&instapass=$instapass"), dirname(__FILE__));
            //echo "http://" . $_SERVER['SERVER_NAME'] . "<br><br>";
            $this->mail->msgHTML(file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/dumbu/worker/resources/emails/contact_form.php?username=$username&useremail=$useremail&usercompany=$usercompany&userphone=$userphone&usermsg=$usermsg"), dirname(__FILE__));

//Replace the plain text body with one created manually
            $this->mail->AltBody = 'User Contact';

//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
            if (!$this->mail->send()) {
                echo "Mailer Error: " . $this->mail->ErrorInfo;
            } else {
                echo "Message sent!";
            }
            $this->mail->smtpClose();
        }

    }

}