<?php

namespace BlackUpload;

/**
 * PHP Library to help you build your own file-sharing website.
 *
 * @version 1.5.2
 * @category File_Upload
 * @package BlackUpload
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackUpload
 */
final class Upload
{
    /**
     * HTML File Input
     *
     * Example: $_FILES['file']
     *
     * @var array
     */
    private $upload_input;

    /**
     * Array For Filename Protection Filter
     *
     * @var array
     */
    private $name_array = [];

    /**
     * Array For Extension Protection Filter
     *
     * @var array
     */
    private $ext_array = [];

    /**
     * Array For MIME Types Protection Fitler
     *
     * @var array
     */
    private $mime_array = [];

    /**
     * Class Controller Path
     *
     * @var string
     */
    private $controller;

    /**
     * File Upload Controller
     *
     * @var string
     */
    private $upload_controller;

    /**
     * Array For the upload folder data
     *
     * Example: ["folder_name" => "upload", "folder_path" => "upload/"]
     *
     * @var array
     */
    private $upload_folder = [];

    /**
     * Size limit filter
     *
     * @var int
     */
    private $size;

    /**
     * Use hashed name instead of the uploaded filename
     *
     * @var boolean
     */
    private $use_hash;

    /**
     * File ID for the database
     *
     * @var string
     */
    private $file_id;

    /**
     * User ID for the database
     *
     * @var string
     */
    private $user_id;

    /**
     * System Logs Array
     *
     * @var array
     */
    private $logs = [];

    /**
     * Enable or Disable file overwriting
     *
     * @var bool
     */
    private $overwrite_file;

    /**
     * An array contains all uploaded files information
     *
     * @var array
     */
    private $files = [];

    /**
     * Image maximum height
     *
     * @var int
     */
    private $max_height;

    /**
     * Image maximum width
     *
     * @var int
     */
    private $max_width;

    /**
     * Image minimum height
     *
     * @var int
     */
    private $min_height;

    /**
     * Image minimum width
     *
     * @var int
     */
    private $min_width;

    /**
     * Array list contains error codes and the messages
     *
     * @var array
     */
    private $message = [
        0 => "File has been uploaded.",
        1 => "Invalid file format.",
        2 => "Failed to get MIME type.",
        3 => "File is forbidden.",
        4 => "Exceeded filesize limit.",
        5 => "Please select a file",
        6 => "File already exist.",
        7 => "Failed to move uploaded file.",
        8 => "The uploaded file's height is too large.",
        9 => "The uploaded file's width is too large.",
        10 => "The uploaded file's height is too small.",
        11 => "The uploaded file's width is too small.",
        12 => "The uploaded file's is too small.",
        13 => "The uploaded file is not a valid image.",
        14 => "Opreation does not exist.",
    ];

    /**
     * Class constructor to initialize the attributes
     *
     * @param array $upload_input
     *  An array of the uploaded file information coming from $_FILES
     * @param array $upload_folder
     *  An array that contains the upload folder full path and name
     * @param string $controller
     *  The folder name of the folder that contains the JSON filters and the class file
     * @param string $upload_controller
     *  The upload controller is the file that contains the factory code like upload.php
     * @param int $size
     *  The maximum size that the class allow uploading
     * @param boolean $use_hash
     *  Use the hashed filename as the upload name instead of the raw format
     * @param boolean $overwrite_file
     *  Enable it if you want to overwrite the file if it exists on the server
     * @param int $max_height
     *  The maximum image height allowed
     * @param int $max_width
     *  The maximum image width allowed
     * @param int $min_height
     *  The minimum image height allowed
     * @param int $min_width
     *  The minimum image width allowed
     * @param string $file_id
     *  A unique id for the file to validate that the file exists
     * @param string $user_id
     *  A unique id for the user to validate the file owner
     * @return void
     */
    public function __construct(
        $upload_input = null,
        $upload_folder = [],
        $controller = null,
        $upload_controller = "upload.php",
        $size = "1 GB",
        $use_hash = false,
        $overwrite_file = true,
        $max_height = null,
        $max_width = null,
        $min_height = null,
        $min_width = null,
        $file_id = null,
        $user_id = null
    ) {
        // initialize attributes
        $this->upload_input = $upload_input;
        $this->upload_folder = $upload_folder;
        $this->controller = $this->sanitize($controller);
        $this->upload_controller = $this->sanitize($upload_controller);
        $this->size = $this->sizeInBytes($this->sanitize($size));
        $this->use_hash = $use_hash;
        $this->overwrite_file = $overwrite_file;
        $this->max_height = $max_height;
        $this->max_width = $max_width;
        $this->min_height = $min_height;
        $this->min_width = $min_width;
        $this->file_id = $file_id;
        $this->user_id = $user_id;
    }

    /**
     * Function to set the upload input when needed.
     *
     * @param array $upload_input
     *  An array contains the uploaded file information coming from $_FILES
     * @return void
     */
    public function setUpload($upload_input)
    {
        $this->upload_input = $upload_input;
    }

    /**
     * Function to set the class controller when needed
     *
     * @param string $controller
     *  The folder name of the folder that contains the JSON filters and the class file
     * @return void
     */
    public function setController($controller)
    {
        $this->controller = $this->sanitize($controller);
    }

    /**
     * Set the upload controller file
     *
     * @param string $upload_controller
     *  The upload controller that contains the factory code like upload.php
     * @return void
     */
    public function setUploadController($upload_controller)
    {
        $this->upload_controller = realpath($this->sanitize($upload_controller));
    }

    /**
     * Set $use_hash to true or false when needed
     *
     * @param boolean $use_hash
     *  Set true to use the hashed filename as the uploaded filename instead of the raw format
     * @return void
     */
    public function useHashAsName($use_hash = false)
    {
        $this->use_hash = $use_hash;
    }

    /**
     * Enable File Uploading Protection and Filters
     *
     * @return void
     */
    public function enableProtection()
    {
        // Enable Level 1 Protection
        $this->name_array = json_decode(
            file_get_contents(
                $this->sanitize($this->controller . "forbidden.json")
            )
        );

        // Enable Level 2 Protection
        $this->ext_array = json_decode(
            file_get_contents(
                $this->sanitize($this->controller . "extension.json")
            )
        );

        // Enable Level 3 Protection
        $this->mime_array = json_decode(
            file_get_contents(
                $this->sanitize($this->controller . "mime.json")
            )
        );
    }

    /**
     * Set forbidden filter array to a custom list when needed
     *
     * @param array $forbidden_array
     *  An array that contains the forbidden filenames like php shell names
     *
     *  Example: ["aaa.php", "file.exe"]
     *
     * @return void
     */
    public function setForbiddenFilter($forbidden_array)
    {
        $this->name_array = $forbidden_array;
    }

    /**
     * Set extension filter array to a custom list when needed
     *
     * @param array $ext_array
     *  An array that contains the allowed file extensions
     *
     *  Example: ["png", "jpg"]
     *
     * @return void
     */
    public function setExtensionFilter($ext_array)
    {
        $this->ext_array = $ext_array;
    }

    /**
     * Set MIME filter array to a custom list when needed
     *
     * @param array $mime_array
     *  An array that contains the allowed file MIME types
     *
     *  Example: ["image/png"]
     *
     * @return void
     */
    public function setMimeFilter($mime_array)
    {
        $this->mime_array = $mime_array;
    }

    /**
     * Set file size limit when needed
     *
     * @param int $size
     *  The size you want to limit for each uploaded file
     * @return void
     */
    public function setSizeLimit($size)
    {
        // Set file size limit to a new limit
        $this->size = $this->fixintOverflow(
            $this->sizeInBytes(
                $this->sanitize($size)
            )
        );
    }

    /**
     * Set upload folder when needed
     *
     * @param array $folder_name
     *  An array contains the upload folder information full path and name
     *
     *  Example: ["folder_name" => "upload", "folder_path" => realpath("upload")]
     *
     * @return void
     */
    public function setUploadFolder($folder_name)
    {
        $this->upload_folder = $folder_name;
    }

    /**
     * Firewall 1: Check file extension
     *
     * @return bool
     *  Return true it the uploaded file extenstion is allowed
     */
    public function checkExtension()
    {
        // Check if the file extension is whitelisted
        if (in_array($this->getExtension(), $this->ext_array)) {
            return true;
        } else {
            $this->addLog(['filename' => $this->getName(), "message" => 1]);
            return false;
        }
    }

    /**
     * Function to return the file input extension
     *
     * @return string
     *  Return the uploaded file extenstion as string
     */
    public function getExtension()
    {
        // Get the file extension
        return strtolower(
            pathinfo($this->getName(), PATHINFO_EXTENSION)
        );
    }

    /**
     * Firewall 2: Check file MIME type
     *
     * @return bool
     *  Return true if the uploaded file MIME type is allowed
     */
    public function checkMime()
    {
        // Get the file MIME type using the browser
        $mime = mime_content_type($this->getTempName());

        // Check if the file MIME type is whitelisted
        if (in_array($mime, $this->mime_array)) {
            // Check if the browser MIME type equals the server MIME type
            if ($mime == $this->getMime()) {
                return true;
            } else {
                $this->addLog(['filename' => $this->getName(), "message" => 1]);
                return false;
            }
        }
    }

    /**
     * Function to get the MIME type using the server
     *
     * @return string
     *  Return the file MIME type as string
     */
    private function getMime()
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mtype = finfo_file($finfo, $this->getTempName());
        if (finfo_close($finfo)) {
            return $mtype;
        } else {
            $this->addLog(['filename' => $this->getName(), "message" => 2]);
            return "application/octet-stream";
        }
    }

    /**
     * Function that return the uploaded file MIME type
     *
     * @return string
     *  Return the file MIME type as string
     */
    public function getFileType()
    {
        return $this->upload_input['type'];
    }

    /**
     * Firewall 3: Check if the filename is forbidden
     *
     * @return bool
     *  Return true if the name is forbidden
     */
    public function checkForbidden()
    {
        if (!(in_array($this->getName(), $this->name_array))) {
            return true;
        } else {
            $this->addLog(['filename' => $this->getName(), "message" => 3]);
            return false;
        }
    }

    /**
     * Firewall 4: Check the file size limit
     *
     * @return bool
     *  Return true if the uploaded file size does not exceed the limit
     */
    public function checkSize()
    {
        if ($this->getSize() <= $this->size) {
            return true; // Return true if the uploaded passed the size limit test
        } else {
            $this->addLog(['filename' => $this->getName(), "message" => 4]);
            return false;
        }
    }

    /**
     * Return the size of the uploaded file as bytes
     *
     * @return float
     *  Return the uploaded file size as bytes
     */
    public function getSize()
    {
        return $this->fixintOverflow($this->upload_input['size']);
    }

    /**
     * Function to check if the HTML input is empty
     *
     * @return bool
     *  Return true if the the input contains a file or false otherwise
     */
    public function checkIfEmpty()
    {
        if ($this->upload_input['error'] == UPLOAD_ERR_NO_FILE) {
            $this->addLog(['filename' => $this->getName(), "message" => 5]);
            return false;
        } else {
            return true;
        }
    }

    /**
     * Return the name of the uploaded file
     *
     * @return string
     *  Return the name of the uploaded file as string
     */
    public function getName()
    {
        return $this->upload_input['name'];
    }

    /**
     * Return the PHP Generated name for the uploaded file
     *
     * @return string
     *  Return the temp name that the PHP generated for the uploaded file
     */
    public function getTempName()
    {
        return $this->upload_input['tmp_name'];
    }

    /**
     * Generate a Qr Code of the download url
     *
     * @param string $qr_size
     *  The size ot the qr code image
     * @return string
     *  Return the qr code image url to display
     */
    public function generateQrCode($qr_size = "150x150")
    {
        $chart_api = "https://chart.googleapis.com/chart?chs=%s&cht=qr&chl=%s&choe=UTF-8";

        return sprintf($chart_api, $this->sanitize($qr_size), $this->generateDownloadLink());
    }

    /**
     * Generate a download link
     *
     * @return string
     *  Return a well formatted download link with a custom download page
     */
    public function generateDownloadLink()
    {
        $file_id = $this->file_id;
        // Return a formated download link
        return sprintf(
            "%s://%s%s/%s?file_id=%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            dirname($_SERVER['REQUEST_URI']),
            "download.php",
            $file_id
        );
    }

    /**
     * Generate a delete link
     *
     * @return string
     *  Return a well formatted delete file link with a custom delete page
     */
    public function generateDeleteLink()
    {
        // Get user paramters [ file_id, user_id ]
        $file_id = $this->file_id;
        $user_id = $this->user_id;

        // Return a formated download link
        return sprintf(
            "%s://%s%s/%s?file_id=%s&user_id=%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            dirname($_SERVER['REQUEST_URI']),
            "delete.php",
            $file_id,
            $user_id
        );
    }

    /**
     * Generate a direct download link
     *
     * @return string
     *  Return a well formatted direct download link without a custom download page
     */
    public function generateDirectDownloadLink()
    {
        $filename = ($this->use_hash ?
            $this->hashName() . "." . $this->getExtension() :
            $this->getName());
        // Return a formated download link
        return sprintf(
            "%s://%s%s/%s/%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            dirname($_SERVER['REQUEST_URI']),
            $this->upload_folder['folder_name'],
            $filename
        );
    }

    /**
     * Get the unique file id
     *
     * @return string
     *  Return the uploaded file Unique id
     */
    public function getFileID()
    {
        return $this->file_id;
    }

    /**
     * Set the unique file id manualy when needed
     *
     * @param string
     *  The unique file id that you want
     * @return void
     */
    public function setFileID($file_id = "")
    {
        $this->file_id = $file_id;
    }

    /**
     * Get the unique user id for security
     *
     * @return string
     *  Return the unique user id
     */
    public function getUserID()
    {
        return $this->user_id;
    }

    /**
     * Set the unique user id manualy when needed
     *
     * @param string
     *  The unique file id that you want
     * @return void
     */
    public function setUserID($user_id = "")
    {
        $this->user_id = $user_id;
    }

    /**
     * Generate a simple upload form
     *
     * @param boolean $multiple
     *  Set true if you want to make the upload input handles multipe files
     * @return string
     *  Return an HTML Upload Form code to use
     */
    public function generateForm($multiple = false)
    {
        $multiple = $multiple ? "multiple" : null;
        // Upload Form Code
        return sprintf('
        <form class="upload_form" action="%s" method="post" enctype="multipart/form-data">
            <input class="upload_input" type="file" name="file" %s />
            <button  name="upload" class="upload_button" type="submit">Upload</button>
        </form>
        ', $this->upload_controller, $multiple);
    }

    /**
     * Generate a multi-upload form
     *
     * @param int $size
     *  The number of inputs in the form
     * @return string
     *  Return an HTML Upload Form code to use
     */
    public function generateMultiInput($size = 5)
    {
        $form = sprintf(
            '<form class="upload_form" action="%s" method="post" enctype="multipart/form-data">',
            $this->upload_controller
        );
        for ($i = 0; $i < $size; $i++) {
            $form .= '
            <div class="container">
                <input class="upload_input" type="file" name="file[]">
            </div>';
        }
        $form .= '<button name="upload" class="upload_button" type="submit">Upload</button>';
        $form .= '</form>';
        return $form;
    }

    /**
     * Return an "SHA1 Hashed Filename" of the uploaded file
     *
     * @return string
     *  Return the file real name using getName() function and hash it using SHA1
     */
    public function hashName()
    {
        return hash(
            "sha1",
            $this->sanitize(basename($this->getName()))
        );
    }

    /**
     * Get the date of the uploaded file
     *
     * @return int|bool
     *  Return the file last modification time or false if an error occurred
     */
    public function getDate()
    {
        return filemtime($this->getTempName());
    }

    /**
     * Function to upload the file to the server
     *
     * @return bool
     *  Return true if the file is uploaded or false otherwise
     */
    public function upload()
    {
        $filename = ($this->use_hash ?
            $this->hashName() . "." . $this->getExtension() :
            $this->getName());

        if (!($this->overwrite_file == true)) {
            if ($this->isFile($this->upload_folder['folder_path'] . "/" . $filename) == false) {
                if ($this->moveFile($filename)) {
                    $this->addLog(['filename' => $this->getName(), "message" => 0]);
                    $this->addFile($this->getJSON());
                    return true;
                }
            } else {
                // Show an error message
                $this->addLog(['filename' => $this->getName(), "message" => 6]);
                return false;
            }
        } else {
            // Function to move the file to the upload folder
            if ($this->moveFile($filename) == true) {
                $this->addLog(['filename' => $this->getName(), "message" => 0]);
                $this->addFile($this->getJSON());
                return true;
            }
        }
    }

    /**
     * Function to upload file to the server using the chunk system
     *
     * @param string $filename
     *  The filename you want to use
     * @return bool
     *  Return true if the file is uploaded or false otherwise
     */
    public function moveFile($filename)
    {
        set_time_limit(0);
        $orig_file_size = $this->getSize();
        $chunk_size = 1024;
        $upload_start = 0;
        $handle = fopen($this->getTempName(), "rb");
        $fp = fopen($this->upload_folder['folder_path'] . "/" . $filename, 'w');

        while ($upload_start < $orig_file_size) {
            $contents = fread($handle, $chunk_size);
            fwrite($fp, $contents);

            $upload_start += strlen($contents);
            fseek($handle, $upload_start);
        }

        fclose($handle);

        if (fclose($fp)) {
            return true;
        } else {
            $this->addLog(['filename' => $this->getName(), "message" => 7]);
            return false;
        }
    }

    /**
     * Fix file input array to make it easy to iterate through it
     *
     * @param array $file_post
     *  The unarranged files array to fix
     * @return array
     *  Return a fixed and arranged files array based on PHP standerds
     */
    public function fixArray($file_post)
    {
        $file_array = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_array[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_array;
    }

    /**
     * Function to create an upload folder and secure it
     *
     * @param string $folder_name
     *  The folder name you want to create as an upload folder
     * @return void
     */
    public function createUploadFolder($folder_name)
    {
        if (!file_exists($folder_name) && !is_dir($folder_name)) {
            mkdir($this->sanitize($folder_name));
            chmod($this->sanitize($folder_name), 0777);

            $this->protectFolder($folder_name);
        }

        $this->setUploadFolder(
            [
                "folder_name" => $folder_name,
                "folder_path" => realpath($folder_name)
            ]
        );
    }

    /**
     * Function to potect a folder
     *
     * @param string $folder_name
     *  The folder name you want protect
     * @return void
     */
    public function protectFolder($folder_name)
    {
        if (!file_exists($this->sanitize($folder_name) . "/" . "index.php")) {
            $content = "<?php http_response_code(403); ?>";
            file_put_contents($this->sanitize($folder_name) . "/" . "index.php", $content);
        }
    }

    /**
     * Function to help with input filtering and sanitizing
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
        $data = strip_tags($data);
        $data = filter_var($data, FILTER_SANITIZE_STRING);
        return $data;
    }

    /**
     * A function that formats file bytes to a readable format.
     *
     * Example: 7201450 to 7.2 MB
     *
     * @param int $bytes
     *  The file size that you want to convert, in bytes
     * @param int $precision
     *  The precision of the conversion how many digits you want after the dot
     * @return string
     *  Return the bytes as readable format
     */
    public function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Return any type of readable storage size to bytes
     *
     * Example: 7.2 MB to 7201450
     *
     * @param int $size
     *  The readable size that you want to convert to bytes
     * @return float
     *  Return the bytes size as float
     */
    public function sizeInBytes($size)
    {
        $unit = "B";
        $units = array("B" => 0, "K" => 1, "M" => 2, "G" => 3, "T" => 4);
        $matches = array();
        preg_match("/(?<size>[\d\.]+)\s*(?<unit>b|k|m|g|t)?/i", $size, $matches);
        if (array_key_exists("unit", $matches)) {
            $unit = strtoupper($matches["unit"]);
        }
        return (floatval($matches["size"]) * pow(1024, $units[$unit]));
    }

    /**
     * Return the files from the upload folder to view them
     *
     * @return array|bool
     *  Return an array the contains all files in the upload folder or false if an error occurred
     */
    public function getUploadDirFiles()
    {
        return scandir($this->upload_folder['folder_path']);
    }

    /**
     * Check if a file exist and it is a real file
     *
     * @param string $file_name
     *  The file you want to cheack if it exist
     * @return bool
     *  Return true if the exist or false otherwise
     */
    public function isFile($file_name)
    {
        $file_name = $this->sanitize($file_name);

        if (file_exists($file_name) && is_file($file_name)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if a directory exist and it is a real directory
     *
     * @param string $dir_name
     *  The name of the folder you want check if it exist
     * @return bool
     *  Return true if the folder exist or false otherwise
     */
    public function isDir($dir_name)
    {
        $dir_name = $this->sanitize($dir_name);

        if (is_dir($dir_name) && file_exists($dir_name)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Create a callback function when needed after or before an operation
     *
     * @param callback $function
     *  A callback function to execute
     * @param mixed $args
     *  A single parameter or an array that contains multiple paramters.
     * @return mixed
     *  Return the callback function output
     */
    public function callback($function, $args = null)
    {
        if (is_callable($function)) {
            if (is_array($args)) {
                return call_user_func_array($function, $args);
            } else {
                return call_user_func($function, $args);
            }
        }
    }

    /**
     * Add a message the system log
     *
     * @param mixed $id
     *  The array index that you want to assign the message to
     * @param mixed $message
     *  The message id from the messages array or as a raw string
     * @return void
     */
    public function addLog($message, $id = null)
    {
        if ($id == null) {
            array_push($this->logs, $message);
        } else {
            $this->logs[$id] = $message;
        }
    }

    /**
     * Get all logs from system log to view them
     *
     * @return array
     *  Return an array that contains all the logs in class logs system
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Get a system log message by an array index id
     *
     * @param mixed $log_id
     *  The logs id to retrive the message
     * @return int
     *  Return the message id to use with the messages array
     */
    public function getLog($log_id)
    {
        return $this->logs[$log_id];
    }

    /**
     * Set file overwriting to true or false
     *
     * @param boolean $status
     *  Set true if you want to enable overwriting or false otherwise
     * @return void
     */
    public function setFileOverwriting($status)
    {
        $this->overwrite_file = $status;
    }

    /**
     * Set php.ini settings using an array
     *
     * Example: setINI(["file_uploads"=>1])
     *
     * @param array $ini_settings
     *  An array the contains the ini settings variables and values
     * @return void
     */
    public function setINI($ini_settings)
    {
        $sttings = [];

        foreach ($ini_settings as $key => $value) {
            ini_set($key, $value);

            $sttings[] = $key . "=" . $value;
        }

        if (is_file("php.ini") == false) {
            touch('php.ini');
        }

        file_put_contents('php.ini', '[PHP]' . "\n" . implode("\n", $sttings));
    }

    /**
     * Ensure the correct value for big ints
     *
     * @param int $int
     * @return float
     */
    public function fixintOverflow($int)
    {
        if ($int < 0) {
            $int += 2.0 * (PHP_INT_MAX + 1);
        }

        return $int;
    }

    /**
     * Get all the uploaded file information in JSON.
     *
     * @return string
     *  Return a JSON string that contains the uploaded file information
     */
    public function getJSON()
    {
        return json_encode(
            [
                "message" => 0,
                "filename" => $this->getName(),
                "filehash" => $this->hashName(),
                "filesize" => $this->formatBytes($this->getSize()),
                "uploaddate" => date("Y/m/d h:i:s A", $this->getDate()),
                "qrcode" => $this->generateQrCode(),
                "downloadurl" => $this->generateDownloadLink(),
                "directlink" => $this->generateDirectDownloadLink(),
                "deletelink" => $this->generateDeleteLink(),
            ]
        );
    }

    /**
     * Function to add a file to the files array
     *
     * @param string $json_string
     *  The JSON string that contains the file information
     * @return void
     */
    public function addFile($json_string)
    {
        array_push($this->files, json_decode($json_string));
    }

    /**
     * Function to return an array with information about all the uploaded files
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Function to get a log message using a message index id
     *
     * @param int $index
     *  The message index from 1 to 14
     * @return string
     *  Return the log message as string
     */
    public function getMessage($index)
    {
        return $this->message[$index];
    }

    /**
     * Include Bootstrap CSS
     *
     * @return string
     *  Return Bootstrap files using CDN
     */
    public function includeBootstrap()
    {
        return '
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" />
        ';
    }

    /**
     * Include jQuery Javascript files
     *
     * @return string
     *  Return jQuery files using CDN
     */
    public function includeJquery()
    {
        return '<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" ></script>
				<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" ></script>
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" ></script>';
    }

    /**
     * Function to create an upload worker using one line of code
     *
     * @param array $upload_input
     *  An array of the upload file information coming from $_FILES
     * @return bool
     *  Return true if the file is uploaded otherwise false
     */
    public function factory($upload_input = null)
    {
        $this->setUploadFolder([
            "folder_name" => "uploads",
            "folder_path" => \realpath("uploads"),
        ]);
        $this->setFileOverwriting(true);
        $this->useHashAsName(false);
        $this->enableProtection();
        $this->file_id = $this->generateUniqueID();
        $this->user_id = $this->generateUniqueID("user-");
        if ($upload_input == null) {
            $this->setUpload($_FILES['file']);
        } else {
            $this->setUpload($upload_input);
        }
        // Check all class 5 protection levels
        if ($this->checkIfEmpty()) {
            if ($this->checkSize()) {
                if (
                    $this->checkForbidden() &&
                    $this->checkExtension() &&
                    $this->checkMime()
                ) {
                    return $this->upload();
                }
            }
        }
    }

    /**
     * Extra Firewall 1: Check an image dimenstions aginst the class dimenstions
     *
     * @param int $opreation
     *  Select opreations from 0 to 5
     * @return bool
     *  Return true if an image size passed this filter otherwise return false
     */
    public function checkDimenstion($opreation = 2)
    {
        $image_data = getimagesize($this->getTempName());
        $width = $image_data[0];
        $height = $image_data[1];

        switch ($opreation) {
            case 0:
                if ($height <= $this->max_height) {
                    return true;
                } else {
                    $this->addLog(['filename' => $this->getName(), "message" => 8]);
                }
                break;

            case 1:
                if ($width <= $this->max_width) {
                    return true;
                } else {
                    $this->addLog(['filename' => $this->getName(), "message" => 9]);
                }
                break;

            case 2:
                if ($width <= $this->max_width && $height <= $this->max_height) {
                    return true;
                } else {
                    $this->addLog(['filename' => $this->getName(), "message" => 8]);
                }
                break;

            case 3:
                if ($height >= $this->min_height) {
                    return true;
                } else {
                    $this->addLog(['filename' => $this->getName(), "message" => 10]);
                }
                break;

            case 4:
                if ($width >= $this->min_width) {
                    return true;
                } else {
                    $this->addLog(['filename' => $this->getName(), "message" => 11]);
                }
                break;

            case 5:
                if ($width >= $this->min_width && $height >= $this->min_height) {
                    return true;
                } else {
                    $this->addLog(['filename' => $this->getName(), "message" => 12]);
                }
                break;

            default:
                $this->addLog(['filename' => $this->getName(), "message" => 14]);
                break;
        }
    }

    /**
     * Function to set the maximum class image dimensions to validate them.
     *
     * @param int $height
     *  The maximum image height
     * @param int $width
     *  The maximum image width
     * @return void
     */
    public function setMaxDimenstion($height = null, $width = null)
    {
        $this->max_height = $height;
        $this->max_width = $width;
    }

    /**
     * Function to set the minimum class image dimensions to validate them.
     *
     * @param int $height
     *  The minimum image height
     * @param int $width
     *  The minimum image width
     * @return void
     */
    public function setMinDimenstion($height = null, $width = null)
    {
        $this->min_height = $height;
        $this->min_width = $width;
    }

    /**
     * Extra Firewall 2: Function to check if the uploaded file is an image
     *
     * @return bool
     *  Return true if the uploaded file is a real image otherwise false
     */
    public function isImage()
    {
        if (in_array($this->getMime(), ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png'])) {
            return true;
        } else {
            $this->addLog(['filename' => $this->getName(), "message" => 13]);
        }
    }

    /**
     * Generate a Unique ID for each uploaded file
     *
     * @param mixed $prefix
     *  Custom string to append before the unique id
     * @return string
     *  Return the uinque id hashed using sha1
     */
    public function generateUniqueID($prefix = "file-")
    {
        return hash("sha1", uniqid($prefix));
    }
}
