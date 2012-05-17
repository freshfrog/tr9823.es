<?php
/**
 * @version   $Id: PlatformFactory.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_PlatformFactory
{
    /** @var string[] */
    protected static $_platforms = array('joomla', 'wordpress', 'phpunit');

    /** @var \RokCommon_Platform_Definition */
    protected static $_current_platform;

    /**
     * @static
     * @return \RokCommon_Platform_Definition
     */
    public static function &getCurrent()
    {
        $ret = null;
        if (isset(self::$_current_platform)) {
            return self::$_current_platform;
        } else {
            foreach (self::$_platforms as $platform) {
                $classname = 'RokCommon_Platform_Definition_' . ucfirst($platform);
                if (class_exists($classname)) {
                    if (call_user_func(array($classname, 'isCurrentlyRunning'))) {
                        self::$_current_platform = new $classname();
                        return self::$_current_platform;
                    }
                }
            }
            return $ret;
        }
    }
}
