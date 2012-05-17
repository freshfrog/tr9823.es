<?php
/**
 * @version   $Id: Header.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Header
{

    /**
     * @var RokCommon_Header
     */
    protected $platform_instance;

    /**
     * @param \RokCommon_Header_Interface $platform_instance
     */
    public function __construct(RokCommon_Header_Interface $platform_instance)
    {
        $this->platform_instance = $platform_instance;
    }

    /**
     * @param $file
     */
    public static function addScript($file)
    {

        $container = RokCommon_Service::getContainer();
        /** @var $self RokCommon_Header_Interface */
        $self      = $container->header;
        $self->addScript($file);
    }

    /**
     * @param $text
     */
    public static function addInlineScript($text)
    {
        $container = RokCommon_Service::getContainer();
        /** @var $self RokCommon_Header_Interface */
        $self      = $container->header;
        $self->addInlineScript($text);
    }

    /**
     * @param $file
     */
    public static function addStyle($file)
    {
        $container = RokCommon_Service::getContainer();
        /** @var $self RokCommon_Header_Interface */
        $self      = $container->header;
        $self->addStyle($file);
    }

    /**
     * @param $text
     */
    public static function addInlineStyle($text)
    {
        $container = RokCommon_Service::getContainer();
        /** @var $self RokCommon_Header_Interface */
        $self      = $container->header;
        $self->addInlineStyle($text);
    }
}
