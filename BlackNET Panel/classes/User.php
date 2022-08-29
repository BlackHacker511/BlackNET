<?php

namespace BlackNET;

/**
 *  A Class to Handle User Data
 *
 * @package BlackNET
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @version 3.7.0
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackNET
 */
class User
{
    /**
     * Database Connection
     *
     * @var Database
     */
    private $db;

    /**
     * User class constructor
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
     * Get all existing users from the database
     *
     * @return array|bool
     */
    public function getUsers()
    {
        $sql = "SELECT * FROM users;";

        $this->db->query($sql);

        if ($this->db->execute()) {
            return $this->db->resultset();
        } else {
            return false;
        }
    }

    /**
     * Function to get the user information
     *
     * @param string $username
     *  The username you want to get his/her information
     * @return object|bool
     *  An object contains the username information otherwise false
     */
    public function getUserData($username)
    {
        $find_by = "";

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $find_by = "email";
        } elseif (is_int($username)) {
            $find_by = "id";
        } else {
            $find_by = "username";
        }

        $sql = sprintf("SELECT * FROM users WHERE %s = %s;", $find_by, ":" . $find_by);

        $this->db->query($sql);

        $this->db->bind(":" . $find_by, $username, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            return $this->db->single();
        } else {
            return false;
        }
    }

    /**
     * Function to know how many users
     *
     * @return int
     *  Return the number of users
     */
    public function numUsers()
    {
        $this->db->query("SELECT * FROM users;");

        if ($this->db->execute()) {
            return $this->db->rowCount();
        }
    }

    /**
     * Function to check if a user exists in the database
     *
     * @param string $username
     *  The username you want to check if it exists
     * @return bool
     *  Return true if the user exists otherwise return false
     */
    public function checkUser($username)
    {
        $find_by = "";

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $find_by = "email";
        } elseif (is_int($username)) {
            $find_by = "id";
        } else {
            $find_by = "username";
        }

        $this->db->query(sprintf("SELECT * FROM users WHERE %s = %s;", $find_by, ":" . $find_by));

        $this->db->bind(":" . $find_by, $username, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            if ($this->db->rowCount()) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Create a new a user when needed
     *
     * @param array $user_array
     * @return bool
     */
    public function createUser($user_array)
    {
        $sql = sprintf(
            "INSERT INTO users (%s) VALUES (%s)",
            implode(", ", array_keys($user_array)),
            ":" . implode(",:", array_keys($user_array))
        );

        $this->db->query($sql);

        foreach ($user_array as $key => $value) {
            $this->db->bind(":" . $key, $value);
        }

        return $this->db->execute();
    }

    /**
     * Update user information when needed
     *
     * @param int $id
     *  The user id you want to update it information
     * @param array $user_array
     *  The new user data that you want to change to
     * @return bool
     *  Return true if user data is updated
     */
    public function updateUser($id, $user_array)
    {

        $array_keys = array_keys($user_array);

        $upate_user_syntax = "UPDATE users SET %s WHERE id = :id;";

        $sql_values = "";

        foreach ($array_keys as $key) {
            $sql_values .= $key . "=" . ":" . $key . ",";
        }

        $sql_values = rtrim($sql_values, ",");

        $this->db->query(sprintf(
            $upate_user_syntax,
            $sql_values
        ));

        foreach ($user_array as $key => $value) {
            $this->db->bind(":" . $key, $value, \PDO::PARAM_STR);
        }

        $this->db->bind(":id", $id, \PDO::PARAM_INT);

        return $this->db->execute();
    }

    /**
     * Delete the username from the database when needed
     *
     * @param string $username
     *  The username you want to delete from the database
     * @return bool
     *  Return true if the username is deleted otherwise false
     */
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";

        $this->db->query($sql);

        $this->db->bind(":id", $id);

        return $this->db->execute();
    }

    /**
     * Enable 2-Factor Authentication for a user
     *
     * @param string $username
     *  The username you want enable 2fa to
     * @param string $secret
     *  The secure secret
     * @return bool
     *  Return true if the 2FA is enabled
     */
    public function enables2fa($username, $secret)
    {
        $sql = "UPDATE users SET s2fa = :auth, secret = :secret WHERE username = :username;";

        $this->db->query($sql);

        $this->db->bind(":auth", 1, \PDO::PARAM_INT);
        $this->db->bind(":secret", $secret, \PDO::PARAM_STR);
        $this->db->bind(":username", $username, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Disable 2-Factor Authentication for a user
     *
     * @param string $username
     *  The username you want disable 2FA to
     * @return bool
     *  Return true if the 2FA is disabled
     */
    public function disable2fa($username)
    {
        $sql = "UPDATE users SET s2fa = :auth, secret = :secret WHERE username = :username;";

        $this->db->query($sql);

        $this->db->bind(":auth", 0, \PDO::PARAM_INT);
        $this->db->bind(":secret", null, \PDO::PARAM_NULL);
        $this->db->bind(":username", $username, \PDO::PARAM_STR);

        return $this->db->execute();
    }

    /**
     * Get the user security question
     *
     * @param string $username
     *  The username you want to get his/her security question
     * @return object|bool
     *  An object conatins the user security question and answer otherwise false
     */
    public function getQuestionByUser($username)
    {
        $this->db->query("SELECT question,answer,sqenable FROM users WHERE username = :user;");

        $this->db->bind(":user", $username, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            return $this->db->single();
        } else {
            return false;
        }
    }

    /**
     * Check if the security question is enabled
     *
     * @param string $username
     *  The username that you want to check if his/her security question is enabled
     * @return bool
     *  Return true if the security question is enabled otherwise false
     */
    public function isQuestionEnabled($username)
    {
        $this->db->query("SELECT sqenable FROM users WHERE username = :user;");

        $this->db->bind(":user", $username, \PDO::PARAM_STR);

        if ($this->db->execute()) {
            $data = $this->db->single();

            if ($data->sqenable == false) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Check if 2FA is enbaled
     *
     * @param string $username
     *  The username you want to check if the 2FA service is enabled for.
     * @return int
     *  Return the 2FA status either true or false
     */
    public function isTwoFAEnabled($username)
    {
        $data = $this->getUserData($username);
        return (int) $data->s2fa;
    }

    /**
     * Return the user 2FA secret
     *
     * @param string $username
     *  The username that you want to get his/her secret
     * @return string
     *  Return the user 2FA secret
     */
    public function getSecret($username)
    {
        $data = $this->getUserData($username);
        return $data->secret;
    }
}
