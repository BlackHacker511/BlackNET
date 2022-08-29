<?php

namespace BlackNET;

/**
 * A class that has some utilities functions
 *
 * @package BlackNET
 * @version 3.7.0
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackNET
 */

class Utils
{

    /**
     * Sanitize value
     *
     * @param string $value
     *  The value of the malicious string you want to sanitize
     * @return string
     *  Return the sanitized string
     */
    public function sanitize($value)
    {
        $data = trim($value);
        $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8");
        $data = filter_var($data, FILTER_SANITIZE_STRING);
        $data = strip_tags($data);
        return $data;
    }

    /**
     * Show custom alerts when needed
     *
     * @param string $message
     *  The message you want to show
     * @param string $style
     *  The style of the message using bootstrap colors
     * @param string $icon
     *  The alert icon using font awesome icons
     * @return string
     *  Return a formatted message as an HTML code
     */
    public function alert($message, $style = "primary", $icon = "info-circle")
    {
        if ($icon != null) {
            $icon = sprintf('<span class="fa fa-%s"></span>', $this->sanitize($icon));
        } else {
            $icon = "";
        }

        return sprintf(
            '<div class="alert alert-%s">%s %s</div>',
            $this->sanitize($style),
            $icon,
            $this->sanitize($message)
        );
    }

    /**
     * Show dismissible alerts when needed
     *
     * @param string $message
     *  The message you want to show
     * @param string $style
     *  The style of the message using bootstrap colors
     * @param string $icon
     *  The alert icon using font awesome icons
     * @return string
     *  Return a formatted message as an HTML code
     */
    public function dismissibleAlert($message, $style = "primary", $icon = "info-circle")
    {
        if ($icon != null) {
            $icon = sprintf('<span class="fa fa-%s"></span>', $this->sanitize($icon));
        } else {
            $icon = "";
        }

        return sprintf(
            '<div class="alert alert-%s alert-dismissible fade show">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>%s %s
             </div>',
            $this->sanitize($style),
            $icon,
            $message
        );
    }

    /**
     * Redirect a user to a page when needed
     *
     * @param string $url
     *  The URL or the page you want to redirect the user to it.
     * @return void
     */
    public function redirect($url)
    {
        header('Location: ' . $url, true, 301);
        exit;
    }

    /**
     * Show an input with a custom value when needed, like CRSF value
     *
     * @param string $name
     *  The name of the input example "CSRF"
     * @param string $value
     *  The value of the input example a CSRF token
     * @param bool $hidden
     *  Set true if you want to make the input hidden otherwise false
     * @return string
     *  Return a formatted input as an HTML code
     */
    public function input($name, $value, $hidden = true)
    {
        $h = ($hidden ? 'hidden' : "");

        $name = $this->sanitize($name);

        return sprintf(
            '<input type="text" value="%s" name="%s" id="%s" %s />',
            $this->sanitize($value),
            $name,
            $name,
            $h
        );
    }

    /**
     * Check if a link is active or not in the navbar
     *
     * @param $page
     *  Page variable exists on every page in this project
     * @param string $page_name
     *  The page name you want to check
     * @return string
     *  Return active or null
     */
    public function linkActive($page, $page_name)
    {
        if (isset($page) && $page != null) {
            return ($page == $page_name) ? "active" : "";
        } else {
            return "";
        }
    }

    /**
     * Encode a string using Base64_URL_Safe Method
     *
     * @param string $string
     *  The string you want to encode as base64
     * @return string
     *  Return the encoded string
     */
    public function base64EncodeUrl($string)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($string));
    }

    /**
     * Decode a string using Base64_URL_Safe Method
     *
     * @param string $string
     *  The base64 string you want to decode as raw string
     * @return string
     *  Return the decoded string
     */
    public function base64DecodeUrl($string)
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $string));
    }

    /**
     * Search for a value inside an associative array
     *
     * @param array $array
     *  The array you want to search inside it
     * @param mixex $key
     *  The kay you want to check is value
     * @param mixed $val
     *  The value you want to find in the array
     * @return bool
     *  Return true if it exists otherwise false
     */
    public function findKeyValue($array, $key, $val)
    {
        foreach ($array as $item) {
            if (is_array($item) && $this->findKeyValue($item, $key, $val)) {
                return true;
            }

            if (isset($item[$key]) && $item[$key] == $val) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check, Validate and Format a URL
     *
     * @param mixed $url
     *  The url you want to check and validate
     * @return mixed
     *  Return a valid url, localhost, or an invalid message
     */
    public function validateURL($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $url_parase = parse_url(filter_var($url, FILTER_SANITIZE_URL));
            return $url_parase['scheme'] . "://" . $url_parase['host'] . "/";
        } elseif (filter_var($url, FILTER_VALIDATE_IP)) {
            return $url;
        } elseif ($url == "localhost") {
            return $url;
        } else {
            return "Domain does not exist";
        }
    }

    /**
     * Check if the provided email address is valid or not
     *
     * @param string $email
     *  The email address you want to check it against the function rules
     * @return bool
     *  Return true if the email address is valid otherwise return false
     */
    public function validateEmail($email)
    {

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $domain = strtolower(substr($email, strpos($email, '@') + 1));

        // A list of popular email providers
        $providers = [
            'gmail.com',
            'hotmail.com',
            'outlook.com',
            'msn.com',
            'outlook.sa',
            'aol.com',
            'protonmail.com'
        ];

        $inarray = in_array($domain, $providers);

        return (filter_var($email, FILTER_VALIDATE_EMAIL) && checkdnsrr($domain) != false && $inarray);
    }

    /**
     * Return the full panel url
     *
     * @return string
     *  Return the full panel url
     */
    public function getPanelURL()
    {
        if (defined("SITE_URL") && SITE_URL != null) {
            return rtrim(SITE_URL, "/");
        } else {
            $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https" : "http") . "://";

            $url = $_SERVER['REQUEST_URI'];

            $parts = explode('/', $url);

            $dir = $_SERVER['SERVER_NAME'];

            $parts_count = count($parts) - 1;

            $i = 0;

            foreach ($parts as $part) {
                if ($parts_count != $i) {
                    $dir .= $part . '/';
                }
                $i = $i + 1;
            }

            return rtrim($http . $dir, '/');
        }
    }

    /**
     * Read and parse a text file
     *
     * @param string $file_name
     *  The file name you want to parse
     * @param string $split_by
     *  The word you want to split the text by
     * @param bool $decode
     *  Set true if you want to decode the text using base64
     * @return array
     *  An array contains the values of the splitted text
     */
    public function parseFile($file_name, $split_by, $decode = false)
    {
        $fn = fopen($file_name, "r");
        $text = fread($fn, filesize($file_name));
        $text = trim($text);
        if ($decode == true) {
            $text = base64_decode($text);
        }
        $lines = explode($split_by, $text);

        return $lines;
    }

    /**
     * Create a cookie that expires in 30 days when needed
     *
     * @param string $name
     *  The cookie name
     * @param mixed $value
     *  The cookie value
     * @return bool
     *  Return true if the cookie is created
     */
    public function createCookie($name, $value)
    {
        if (!isset($_COOKIE[$name])) {
            if (setcookie($name, $value, time() + 60 * 60 * 24 * 30, "/")) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Delete a cookie when needed
     *
     * @param string $name
     *  The cookie name
     * @return bool
     *  Return true if the cookie is removed
     */
    public function deleteCookie($name)
    {
        if (isset($_COOKIE[$name])) {
            if (setcookie($name, "", time() - 3600, "/")) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Return a clean host name from a url
     *
     * @param string $url
     *  The url you want to clean and convert to hostname
     * @return string
     *  A hostname from the provided url
     */
    public function cleanHost($url)
    {
        return str_replace(
            ['http://', "https://", "/", "www."],
            ['', '', '', ''],
            $url
        );
    }

    /**
     * Enqueue a stylesheet file when needed
     *
     * @param string $style_path
     *  The fulll path for the stylesheet file
     * @return bool
     */
    public function style($style_path)
    {
        $site_url = $this->getPanelURL();
        echo "<link href=\"{$site_url}/assets/{$style_path}\" rel=\"stylesheet\" />" . "\n";
        return true;
    }

    /**
     * Enqueue a javascript file when needed
     *
     * @param string $script_path
     *  The fulll path for the javascript file
     * @return bool
     */
    public function script($script_path)
    {
        $site_url = $this->getPanelURL();
        echo "<script src=\"{$site_url}/assets/{$script_path}\"></script>" . "\n";
        return true;
    }

    /**
     * Delete a directory
     *
     * @param string $dir
     *  The directory you want to delete
     * @return bool
     *  Returns true if the file or folder is removed or false otherwise
     **/
    public function deleteDirectory($dir)
    {
        if (is_dir($dir)) {
            $files = glob($dir . '*', GLOB_MARK);

            foreach ($files as $file) {
                $this->deleteDirectory($file);
            }

            return rmdir($dir);
        } elseif (is_file($dir)) {
            return unlink($dir);
        } else {
            return false;
        }
    }
}
