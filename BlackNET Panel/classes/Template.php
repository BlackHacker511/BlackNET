<?php

namespace BlackNET;

/**
 * Simple Template Engine to handle layouts
 *
 * @package BlackNET
 * @version 3.7.0
 * @author Black.Hacker <farisksa79@protonmail.com>
 * @license MIT
 * @link https://github.com/BlackHacker511/BlackNET
 */

class Template
{
    /**
     * Template layouts folder name
     *
     * @var string
     */
    private $layouts_folder;

    /**
     * Template layout filename
     *
     * @var string
     */
    private $layout_name;

    /**
     * Template Arguments
     *
     * Example: ['username' => 'admin']
     *
     * @var array
     */
    private $args;

    /**
     * Template class constructor
     *
     * @param string $layouts_folder
     *  The name of the folder that contains the layout files
     * @return void
     */
    public function __construct($layouts_folder)
    {
        $this->layouts_folder = $layouts_folder . DIRECTORY_SEPARATOR;
    }

    /**
     * Set the layouts folder name when needed
     *
     * @param string $layouts_folder
     *  The name of the folder that contains the layout files
     * @return void
     */
    public function setLayoutPath($layouts_folder)
    {
        $this->layouts_folder = $layouts_folder . DIRECTORY_SEPARATOR;
    }

    /**
     * Set the layout filename when needed
     *
     * @param string $layout_name
     *  The layout filename without file extension
     * @return void
     */
    public function setLayoutName($layout_name)
    {
        $this->layout_name = $layout_name;
    }

    /**
     * Set the template arguments with an array
     *
     * @param array $args
     *  An array contains template arguments
     * @return void
     */
    public function setArgs($args = [])
    {
        $this->args = $args;
    }

    /**
     * A simple method to render a template and replace its variables with custom values
     *
     * @param string $layout_name
     *  The layout file name in the template folder to render it
     * @param array $args
     *  An array contains the layout variables and the values to render inside the template
     * @return string|bool
     *  Return a layout with the values or false if a template doesn't exist
     */
    public function loadTemplate($layout_name, $args = [])
    {
        $layout_path = $this->layouts_folder . $layout_name . '.html';

        if (file_exists($layout_path)) {
            $template = file_get_contents($layout_path);

            if (is_array($args) || count($args) > 0) {
                foreach ($args as $key => $value) {
                    $template = str_replace('{' . $key . '}', $value, $template);
                }
            }

            return $template;
        } else {
            return false;
        }
    }

    /**
     * Render a template using the class settings
     *
     * @return string
     *  Returns the template code as string
     */
    public function renderTemplate()
    {
        if (is_array($this->args) && !(empty($this->args))) {
            return $this->loadTemplate($this->layout_name, $this->args);
        } else {
            return $this->loadTemplate($this->layout_name);
        }
    }

    /**
     * Render a template using the file include and variable extraction
     *
     * @param string $layout_name
     *  The layout filename in the template folder to render it
     * @param array $args
     *  An array contains the layout variables and the values to render inside the template
     * @return bool
     *  Return true
     */
    public function includeTemplate($layout_name = "", $args = [])
    {
        if ($layout_name == "") {
            $layout_name = $this->layout_name;
        }

        if (empty($args) || !is_array($args)) {
            $args = $this->args;
        }
        extract($args);
        require_once $layout_name;
        return true;
    }
}
