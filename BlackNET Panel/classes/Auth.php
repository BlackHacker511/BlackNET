<?php

namespace BlackNET;

/**
 * Class to Handle User Auth
 *
 * @package BlackNET
 * @version 3.7.0
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackNET
 */
class Auth
{

    /**
     * Database Connection
     *
     * @var Database
     */
    private $db;

    /**
     * Utils Connection
     *
     * @var Utils
     */
    private $utils;

    /**
     * Auth class constructor
     *
     * @param object $database
     *  An object from the Database class
     * @param object $utils
     *  An object from the Utils class
     * @return void
     */
    public function __construct($database, $utils)
    {
        $this->db = $database;

        $this->utils = $utils;
    }

    /**
     * Generate a device id based on the browser user agent
     *
     * @return string
     *  Returns the user device id
     */
    public function generateDeviceID()
    {
        return hash("sha256", $this->utils->sanitize($_SERVER['HTTP_USER_AGENT']));
    }

    /**
     * Check login information with brute force protection
     *
     * @param mixed $username
     *  A username used for authentication
     * @param mixed $password
     *  A password used for authentication
     * @return int
     *  A status code used to validate the user
     */
    public function newLogin($username, $password)
    {
        $total_failed_login = 5;
        $lockout_time = 10;
        $account_locked = false;

        $this->db->query(
            'SELECT failed_login, last_login FROM users WHERE username = (:user) LIMIT 1;'
        );

        $this->db->bind(':user', $username, \PDO::PARAM_STR);

        $this->db->execute();

        $row = $this->db->single();

        if (($this->db->rowCount() == 1) && ($row->failed_login >= $total_failed_login)) {
            $last_login = strtotime($row->last_login);
            $timeout = $last_login + ($lockout_time * 60);
            $timenow = time();

            if ($timenow < $timeout) {
                $account_locked = true;
                return 403;
            }
        }

        $this->db->query('SELECT * FROM users WHERE username = (:user) LIMIT 1;');

        $this->db->bind(':user', $username);

        $this->db->execute();

        $row = $this->db->single();

        if (
            ($this->db->rowCount() == 1) &&
            (password_verify($password, $row->password)) &&
            ($account_locked == false)
        ) {
            $last_login = $row->last_login;

            $this->db->query('UPDATE users SET failed_login = 0 WHERE username = (:user) LIMIT 1;');

            $this->db->bind(':user', $username, \PDO::PARAM_STR);

            $this->db->execute();

            return 200;
        } else {
            sleep(rand(2, 4));

            $this->db->query(
                'UPDATE users SET failed_login = (failed_login + 1) WHERE username = (:user) LIMIT 1;'
            );

            $this->db->bind(':user', $username, \PDO::PARAM_STR);

            $this->db->execute();

            return 401;
        }

        $this->db->query('UPDATE users SET last_login = now() WHERE username = (:user) LIMIT 1;');

        $this->db->bind(':user', $username, \PDO::PARAM_STR);

        $this->db->execute();
    }

    /**
     * Check the browser unique id to verify the user
     *
     * @param string $uniqueid
     *  The current session unique id to check aginst cookies
     * @return bool
     *  Return true if the token is correct false otherwise
     */
    public function checkDeviceId($uniqueid)
    {
        if (isset($_COOKIE['2fa'])) {
            if (isset($_COOKIE['device_id'])) {
                if ($_COOKIE['device_id'] == $uniqueid) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Check CSRF token of the user to authenticate requests
     *
     * @param string $user_token
     *  The user token from the hidden form field
     * @param string $session_token
     *  The CSRF token from the session
     * @return bool
     *  Returns true if the tokens are equal otherwise false
     */
    public function checkToken($user_token, $session_token)
    {
        return (isset($session_token) && $user_token == $session_token);
    }

    /**
     * Generate a new CSRF Token when needed
     *
     * @return void
     */
    public function generateSessionToken($distroyToken = false)
    {
        if ($distroyToken == true) {
            $this->destroySessionToken();
        }
        $_SESSION['csrf'] = hash("sha256", uniqid() . $_SESSION['current_ip'] . session_id());
    }

    /**
     * Delete the old CSRF token when needed
     *
     * @return void
     */
    public function destroySessionToken()
    {
        unset($_SESSION['csrf']);
    }
}
