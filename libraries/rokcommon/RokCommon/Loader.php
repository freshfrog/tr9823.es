<?php
 /**
  * @version   $Id: Loader.php 48519 2012-02-03 23:18:52Z btowles $
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */
 
interface RokCommon_Loader {
    const FILE_EXTENSION = '.php';

    /**
     * @abstract
     * @param  string $className the class name to look for and load
     * @return bool True if the class was found and loaded.
     */
    function loadClass($className);
}
