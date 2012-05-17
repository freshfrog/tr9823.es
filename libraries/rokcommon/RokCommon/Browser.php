<?php
/**
 * @version   $Id: Browser.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('ROKCOMMON') or die;

/**
 * @package    RokCommon
 */
class RokCommon_Browser
{
    /**
     * @var RokCommon_Browser
     */
    protected static $instance;

    /** @var string */
    protected $_ua;

    /** @var string */
    protected $name;

    /** @var string */
    protected $version;

    /** @var string */
    protected $shortversion;

    /** @var string */
    protected $platform;

    /** @var string */
    protected $engine;

    /** @var array */
    protected $_checks = array();


    /**
     * @static
     * @return RokCommon_Browser
     */
    public static function &getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *
     */
    protected function __construct()
    {
        $this->_ua = $_SERVER['HTTP_USER_AGENT'];
        $this->_checkPlatform();
        $this->_checkBrowser();
        $this->_checkEngine();

        // add short version
        if ($this->version != 'unknown') $this->shortversion = substr($this->version, 0, strpos($this->version, '.')); else $this->shortversion = 'unknown';
    }

    /**
     */
    protected function _checkPlatform()
    {
        if (preg_match("/iPhone/", $this->_ua) || preg_match("/iPod/", $this->_ua)) {
            $this->platform = "iphone";
        } elseif (preg_match("/iPad/", $this->_ua)) {
            $this->platform = "ipad";
        } elseif (preg_match("/Android/", $this->_ua)) {
            $this->platform = "android";
        } elseif (preg_match("/Mobile/i", $this->_ua)) {
            $this->platform = "mobile";
        } elseif (preg_match("/win/i", $this->_ua)) {
            $this->platform = "win";
        } elseif (preg_match("/mac/i", $this->_ua)) {
            $this->platform = "mac";
        } elseif (preg_match("/linux/i", $this->_ua)) {
            $this->platform = "linux";
        } else {
            $this->platform = "unknown";
        }

        return $this->platform;
    }

    /**
     */
    protected function _checkEngine()
    {
        switch ($this->name) {
            case 'ie':
                $this->engine = 'trident';
                break;
            case 'minefield':
            case 'firefox':
                $this->engine = 'gecko';
                break;
            case 'android':
            case 'ipad':
            case 'iphone':
            case 'chrome':
            case 'safari':
                $this->engine = 'webkit';
                break;
            case 'opera':
                $this->engine = 'presto';
                break;
            default:
                $this->engine = 'unknown';
                break;
        }
    }

    /**
     */
    protected function _checkBrowser()
    {
        // IE
        if (preg_match('/msie/i', $this->_ua) && !preg_match('/opera/i', $this->_ua)) {
            $result        = explode(' ', stristr(str_replace(';', ' ', $this->_ua), 'msie'));
            $this->name    = 'ie';
            $this->version = $result[1];
        } // Firefox
        elseif (preg_match('/Firefox/', $this->_ua)) {
            $result        = explode('/', stristr($this->_ua, 'Firefox'));
            $version       = explode(' ', $result[1]);
            $this->name    = 'firefox';
            $this->version = $version[0];
        } // Minefield
        elseif (preg_match('/Minefield/', $this->_ua)) {
            $result        = explode('/', stristr($this->_ua, 'Minefield'));
            $version       = explode(' ', $result[1]);
            $this->name    = 'minefield';
            $this->version = $version[0];
        } // Chrome
        elseif (preg_match('/Chrome/', $this->_ua)) {
            $result        = explode('/', stristr($this->_ua, 'Chrome'));
            $version       = explode(' ', $result[1]);
            $this->name    = 'chrome';
            $this->version = $version[0];
        } //Safari
        elseif (preg_match('/Safari/', $this->_ua) && !preg_match('/iPhone/', $this->_ua) && !preg_match('/iPod/', $this->_ua) && !preg_match('/iPad/', $this->_ua)) {
            $result     = explode('/', stristr($this->_ua, 'Version'));
            $this->name = 'safari';
            if (isset ($result[1])) {
                $version       = explode(' ', $result[1]);
                $this->version = $version[0];
            } else {
                $this->version = 'unknown';
            }
        } // Opera
        elseif (preg_match('/opera/i', $this->_ua)) {
            $result = stristr($this->_ua, 'opera');

            if (preg_match('/\//', $result)) {
                $result        = explode('/', $result);
                $version       = explode(' ', $result[1]);
                $this->name    = 'opera';
                $this->version = $version[0];
            } else {
                $version       = explode(' ', stristr($result, 'opera'));
                $this->name    = 'opera';
                $this->version = $version[1];
            }
        } // iPhone/iPod
        elseif (preg_match('/iPhone/', $this->_ua) || preg_match('/iPod/', $this->_ua)) {
            $result     = explode('/', stristr($this->_ua, 'Version'));
            $this->name = 'iphone';
            if (isset ($result[1])) {
                $version       = explode(' ', $result[1]);
                $this->version = $version[0];
            } else {
                $this->version = 'unknown';
            }
        } // iPad
        elseif (preg_match('/iPad/', $this->_ua)) {
            $result     = explode('/', stristr($this->_ua, 'Version'));
            $this->name = 'ipad';
            if (isset ($result[1])) {
                $version       = explode(' ', $result[1]);
                $this->version = $version[0];
            } else {
                $this->version = 'unknown';
            }
        } // Android
        elseif (preg_match('/Android/', $this->_ua)) {
            $result     = explode('/', stristr($this->_ua, 'Version'));
            $this->name = 'android';
            if (isset ($result[1])) {
                $version       = explode(' ', $result[1]);
                $this->version = $version[0];
            } else {
                $this->version = "unknown";
            }
        } else {
            $this->name    = "unknown";
            $this->version = "unknown";
        }
    }

    /**
     * @return string
     */
    public static function getShortName()
    {
        $self = self::getInstance();
        return $self->name . $self->shortversion;
    }

    /**
     * @return string
     */
    public static function getUa()
    {
        $self = self::getInstance();
        return $self->_ua;
    }

    /**
     * @return string
     */
    public static function getEngine()
    {
        $self = self::getInstance();
        return $self->engine;
    }

    /**
     * @return string
     */
    public static function getName()
    {
        $self = self::getInstance();
        return $self->name;
    }

    /**
     * @return string
     */
    public static function getPlatform()
    {
        $self = self::getInstance();
        return $self->platform;
    }

    /**
     * @return string
     */
    public static function getShortversion()
    {
        $self = self::getInstance();
        return $self->shortversion;
    }

    /**
     * @return string
     */
    public static function getVersion()
    {
        $self = self::getInstance();
        return $self->version;
    }
}