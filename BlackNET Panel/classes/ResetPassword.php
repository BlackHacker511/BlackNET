<?php

namespace BlackNET;

/**
 * A class that handles Reset Password Requsts
 *
 * @package BlackNET
 * @version 3.7.0
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackNET
 */

class ResetPassword
{
    /**
     * Database Connection
     *
     * @var Database
     */
    private $db;

    /**
     * User Class
     *
     * @var User
     */
    private $user;

    /**
     * User Class
     *
     * @var Utils
     */
    private $utils;

    /**
     * Template Class
     *
     * @var Template
     */
    private $tpl;

    /**
     * ResetPassword class constructor
     *
     * @param object $database
     *  An object from the Database class
     * @param object $user
     *  An object from the User class
     * @param object $utils
     *  An object from the Utils class
     * @param object $tpl
     *  An object from the Template class
     * @return void
     */
    public function __construct($database, $user, $utils, $tpl)
    {
        $this->db = $database;

        $this->user = $user;

        $this->utils = $utils;

        $this->tpl = $tpl;
    }

    /**
     * Generate a secure sha1 token
     *
     * @return string
     *  Return a secure SHA token
     */
    public function generateToken()
    {
        return sha1(uniqid("token"));
    }

    /**
     * Send an email to the user with the reset password link
     *
     * @param string $username
     *  The username you want to send a reset password message to
     * @return bool return
     *  True if the message is send otherwise false
     */
    public function sendMessage($username)
    {
        $sendmail = new Mailer($this->db);

        if ($this->user->checkUser($username) != true) {
            return false;
        } else {
            $token = $this->generateToken();
            $rows = $this->user->getUserData($username);
            $email = $rows->email;

            $this->db->query("INSERT INTO tokens (username,token) VALUES (:username,:token)");

            $this->db->bind(":username", $rows->username, \PDO::PARAM_STR);
            $this->db->bind(":token", $token, \PDO::PARAM_STR);

            if ($this->db->execute()) {
                $sendmail->sendMessage(
                    $email,
                    "Reset password instructions",
                    $this->tpl->loadTemplate("reset_password", [
                        'username' => $rows->username,
                        'actual_link' => $this->utils->getPanelURL(),
                        'token' => $token
                    ])
                );
            }
            return true;
        }
    }

    /**
     * Update the user password
     *
     * @param string $key
     *  The token to verfiy the request
     * @param string $username
     *  The username you want to update it password
     * @param string $password
     *  The new password that the user chooses
     * @return bool
     *  True if the password is updated otherwise false
     */
    public function updatePassword($key, $username, $password)
    {
        if (strlen($password) >= 8) {
            $this->db->query("UPDATE users SET password = :password WHERE username = :username");

            $this->db->bind(":password", password_hash($password, PASSWORD_BCRYPT), \PDO::PARAM_STR);
            $this->db->bind(":username", $username, \PDO::PARAM_STR);

            if ($this->db->execute()) {
                return $this->deleteToken($key);
            }
        } else {
            return false;
        }
    }

    /**
     * Get the username using the SHA1 token
     *
     * @param string $token
     *  The secure token to verfiy the request
     * @return object|bool
     *  An object contains the username or false
     */
    public function getUserAssignToToken($token)
    {
        $this->db->query("SELECT username FROM tokens WHERE token = :token limit 1");

        $this->db->bind(":token", $token, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            return $this->db->single();
        } else {
            return false;
        }
    }

    /**
     * Delete the token from the database
     *
     * @param string $token
     *  The secure token you want to delete
     * @return bool
     *  Return true if the token is deleted otherwise false
     */
    public function deleteToken($token)
    {
        $this->db->query("DELETE FROM tokens WHERE token = :token");

        $this->db->bind(":token", $token, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Check if the token exists or not
     *
     * @param string $token
     *  The secure token you want to check
     * @return bool
     *  Return true if the token exists otherwise false
     */
    public function isExist($token)
    {
        $this->db->query("SELECT * FROM tokens WHERE token = :token");

        $this->db->bind(":token", $token, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            if ($this->db->rowCount()) {
                if ($this->isExpired($token) != false) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Check if the token is expired or not
     *
     * @param string $key
     *  The secure token you want to check
     * @return bool
     *  Return true if the token expired otherwise false
     */
    public function isExpired($key)
    {
        $this->db->query("SELECT * FROM tokens WHERE token = :token");

        $this->db->bind(":token", $key, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            $data = $this->db->single();

            $diff = time() - strtotime($data->created_at);

            if (round($diff / 3600) >= 24) {
                $this->deleteToken($key);
                return false;
            } else {
                return true;
            }
        }
    }
}
