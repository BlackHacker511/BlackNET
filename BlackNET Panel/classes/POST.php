<?php

namespace BlackNET;

/**
 * Class to handles POST Requests
 *
 * @package BlackNET
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @version 3.7.0
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackNET
 */
class POST
{

    /**
     * Folder name that we want to write in it
     *
     * @var string
     */
    private $folder_name;

    /**
     * The file name that we want to write inside it
     *
     * @var string
     */
    private $file_name;

    /**
     * The data we want to put inside $file_name
     *
     * @var string
     */
    private $data;

    /**
     * the file permissions mode
     *
     * @var string
     */
    private $mode;

    /**
     * A Method to prepare class properties
     *
     * @param string $folder_name
     *  The name of the folder that you want to copy the file to it
     * @param string $file_name
     *  The name of the file that you want to put the data inside it
     * @param string $data
     *  The data stream that you want to write inside the file must be a string
     * @param string $mode
     *  The mode parameter specifies the type of access you require to the stream. [optional]
     * @return void
     */
    public function prepare(string $folder_name, string $file_name, string $data, string $mode = "w")
    {
        $this->folder_name = $folder_name;
        $this->file_name = $file_name;
        $this->data = $data;
        $this->mode = $mode;
    }

    /**
     * A method to sanitize and filter data
     *
     * @param string $data
     *  The value of the malicious string you want to sanitize
     * @return string
     *  Return the sanitized string
     */
    public function sanitize($string)
    {
        $data = trim($string);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        $data = filter_var($data, FILTER_SANITIZE_STRING);
        $data = stripslashes($data);
        return $data;
    }

    /**
     * A method to write the prepared data to a file
     *
     * @return bool
     *  Return true if the created otherwise false
     */
    public function write()
    {
        $data = isset($this->data) ? $this->data : "This is incorrect";

        if ($this->folder_name == "www") {
            $myfile = fopen($this->file_name, $this->mode);
        } else {
            if (!file_exists($this->folder_name) && !is_dir($this->folder_name)) {
                mkdir($this->folder_name);
            }

            $myfile = fopen($this->folder_name . "/" . $this->file_name, $this->mode);
        }

        fwrite($myfile, $data . "\n");

        fclose($myfile);

        return true;
    }
}
