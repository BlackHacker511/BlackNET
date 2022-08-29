<?php

namespace BlackNET;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Simple Class that handles emails created using PHPMailer
 *
 * @package BlackNET
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @version 3.7.0
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackNET
 */
class Mailer
{
    /**
     * Database Connection
     *
     * @var Database
     */
    private $db;

    /**
     * Mailer class constructor
     *
     * @param object $database
     *  An object from the Database class
     * @return void
     */
    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * Get SMTP data to use with PHPMailer
     *
     * @return array
     *  Return an array containing the SMTP settings
     */
    public function getSMTP()
    {
        $this->db->query(
            "SELECT setting_key,setting_value FROM settings WHERE setting_key LIKE :like"
        );

        $this->db->bind(":like", 'smtp%');

        if ($this->db->execute()) {
            $settings = $this->db->resultset();
            $settings_array = array();
            foreach ($settings as $setting) {
                $settings_array[$setting->setting_key] = $setting->setting_value;
            }
            return $settings_array;
        }
    }

    /**
     * Check if SMTP is enabled then use PHPMailer to send a message
     *
     * @param string $email
     *  The email you want to send a message to
     * @param string $subject
     *  The message subject
     * @param string $body
     *  The message body plain or HTML
     * @return bool
     *  Return true if the message is sent or false otherwise
     */
    public function sendMessage($email, $subject, $body)
    {
        $smtp = $this->getSMTP(true);

        $mail = new PHPMailer(false);

        if ($smtp['smtp_status'] == true) {
            $mail->isSMTP();

            $mail->Host = $smtp['smtp_security'] . "://" . $smtp['smtp_host'] . ":" . $smtp['smtp_port'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtp['smtp_username'];
            $mail->Password = base64_decode($smtp['smtp_password']);
        }

        $mail->setFrom(ADMIN_EMAIL, 'BlackNET');

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = $subject;

        $mail->Body = $body;

        return $mail->send();
    }
}
