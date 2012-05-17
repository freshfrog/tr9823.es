<?php
/**
 * @version   $Id: Default.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Loader_Default implements RokCommon_Loader
{
    const NAME     = 'DEFAULT';
    const PRIORITY = 10;
    /**
     * @var string
     */
    private $_fileExtension = '.php';

    /**
     * @var string
     */
    private $_namespaceSeparator = '\\';

    /**
     * @var RokCommon_Loader_Default_Path[]
     */
    private $_paths = array();

    /**
     * @static
     * @return RokCommon_Loader
     */
    public static function getInstance()
    {
        return RokCommon_ClassLoader::getLoader(self::NAME);
    }

    public static function register($path, $namespace = null)
    {
        $instance = self::getInstance();
        $instance->addPath($path, $namespace);
    }

    public function __construct()
    {

    }

    /**
     * @param      $path
     * @param null $namespace
     *
     * @return void
     */
    public function addPath($path, $namespace = null)
    {
        if (!array_key_exists($path, $this->_paths)) {
            $this->_paths[$path] = new RokCommon_Loader_Default_Path($path, $namespace);
        }
    }

    public function removePath($path)
    {
        if (array_key_exists($path, $this->_paths)) {
            unset($this->_paths[$path]);
        }
    }

    /**
     * Sets the namespace separator used by classes in the namespace of this class loader.
     *
     * @param string $sep The separator to use.
     */
    public function setNamespaceSeparator($sep)
    {
        $this->_namespaceSeparator = $sep;
    }

    /**
     * Gets the namespace seperator used by classes in the namespace of this class loader.
     *
     * @return string
     */
    public function getNamespaceSeparator()
    {
        return $this->_namespaceSeparator;
    }

    /**
     * Sets the file extension of class files in the namespace of this class loader.
     *
     * @param string $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->_fileExtension = $fileExtension;
    }

    /**
     * Gets the file extension of class files in the namespace of this class loader.
     *
     * @return string $fileExtension
     */
    public function getFileExtension()
    {
        return $this->_fileExtension;
    }


    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     *
     * @return bool
     */
    public function loadClass($className)
    {
        foreach ($this->_paths as $path) {
            if (null === $path->getNamespace() || $path->getNamespace() === substr($className, 0, strlen($path->getNamespace()))) {
                $fileName      = '';
                $namespace     = '';
                $namespacePath = '';
                if (false !== ($lastNsPos = strripos($className, $this->_namespaceSeparator))) {
                    $namespace     = substr($className, 0, $lastNsPos);
                    $className     = substr($className, $lastNsPos + 1);
                    $namespacePath = str_replace($this->_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
                }
                $className_path_parts     = explode('_', $className);
                $className_filename_parts = array();

                while (true) {
                    if (count($className_filename_parts) > 0) {
                        $fileName       = implode(DIRECTORY_SEPARATOR, $className_path_parts) . DIRECTORY_SEPARATOR . implode('_', $className_filename_parts) . $this->_fileExtension;
                        $full_file_path = ($path->getIncludePath() !== null ? $path->getIncludePath() . DIRECTORY_SEPARATOR : '') . $namespacePath . $fileName;
                        if (file_exists($full_file_path) && is_readable($full_file_path)) {
                            require($full_file_path);
                            return true;
                        }
                    }
                    if (count($className_path_parts) > 0) {
                        array_unshift($className_filename_parts, array_pop($className_path_parts));
                    } else {
                        break; // no more class name parts
                    }
                }
            }
        }
        return false;
    }
}


class RokCommon_Loader_Default_Path
{
    /**
     * @var string
     */
    private $_namespace;

    /**
     * @var string
     */
    private $_includePath;

    public function __construct($includePath = null, $ns = null)
    {
        $this->_namespace   = $ns;
        $this->_includePath = $includePath;
    }

    public function setIncludePath(string $includePath)
    {
        $this->_includePath = $includePath;
    }

    public function getIncludePath()
    {
        return $this->_includePath;
    }

    public function setNamespace(string $namespace)
    {
        $this->_namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->_namespace;
    }
}
