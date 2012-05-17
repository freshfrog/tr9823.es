<?php
/**
 * @version   $Id: Joomla15.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Logger_Joomla15 extends RokCommon_Logger_AbstractLogger
{
    /**
     * @var \JLog
     */
    protected $instance;

    /**
     * @param array  $loglevels
     * @param string $file
     * @param null   $options
     * @param null   $path
     */
    public function __construct(array $loglevels = array('ALL'), $file = 'error.php', $options = null, $path = null)
    {

        parent::__construct($loglevels);

        jimport('joomla.error.log');
        $this->instance = JLog::getInstance($file, $options, $path);
    }

    /**
     * General trace  messages
     *
     * @param string    $message    The message for the log
     * @param Exception $throwable  The Exception for the log
     */
    public function trace($message, $throwable = null)
    {
        if (!$this->isLevelEnabled(RokCommon_Logger::TRACE)) return;
        $errorlog            = array();
        $errorlog['level']   = 'TRACE';
        $errorlog['comment'] = $message;
        $this->instance->addEntry($errorlog);
    }

    /**
     * Send a debug message to the log
     *
     * @param string    $message    The message for the log
     * @param Exception $throwable  The Exception for the log
     */
    public function debug($message, $throwable = null)
    {
        if (!$this->isLevelEnabled(RokCommon_Logger::DEBUG)) return;
        $errorlog            = array();
        $errorlog['level']   = 'DEBUG';
        $errorlog['comment'] = $message;
        $this->instance->addEntry($errorlog);
    }

    /**
     * Send a Info Message to the log
     *
     * @param string    $message    The message for the log
     * @param Exception $throwable  The Exception for the log
     */
    public function info($message, $throwable = null)
    {
        if (!$this->isLevelEnabled(RokCommon_Logger::INFO)) return;
        $errorlog            = array();
        $errorlog['level']   = 'INFO';
        $errorlog['comment'] = $message;
        $this->instance->addEntry($errorlog);
    }

    /**
     * Send a notice to the log
     *
     * @param string    $message    The message for the log
     * @param Exception $throwable  The Exception for the log
     */
    public function notice($message, $throwable = null)
    {
        if (!$this->isLevelEnabled(RokCommon_Logger::NOTICE)) return;
        $errorlog            = array();
        $errorlog['level']   = 'NOTICE';
        $errorlog['comment'] = $message;
        $this->instance->addEntry($errorlog);
    }

    /**
     * Send a warning to the log
     *
     * @param string    $message    The message for the log
     * @param Exception $throwable  The Exception for the log
     */
    public function warning($message, $throwable = null)
    {
        if (!$this->isLevelEnabled(RokCommon_Logger::WARNING)) return;
        $errorlog            = array();
        $errorlog['level']   = 'WARNING';
        $errorlog['comment'] = $message;
        $this->instance->addEntry($errorlog);
    }

    /**
     * Send an Error message to the log
     *
     * @param string    $message    The message for the log
     * @param Exception $throwable  The Exception for the log
     */
    public function error($message, $throwable = null)
    {
        if (!$this->isLevelEnabled(RokCommon_Logger::ERROR)) return;
        $errorlog            = array();
        $errorlog['level']   = 'ERROR';
        $errorlog['comment'] = $message;
        $this->instance->addEntry($errorlog);
    }

    /**
     * Send a Fatal message to the log
     *
     * @param string    $message    The message for the log
     * @param Exception $throwable  The Exception for the log
     */
    public function fatal($message, $throwable = null)
    {
        if (!$this->isLevelEnabled(RokCommon_Logger::FATAL)) return;
        $errorlog            = array();
        $errorlog['level']   = 'FATAL';
        $errorlog['comment'] = $message;
        $this->instance->addEntry($errorlog);
    }

}
