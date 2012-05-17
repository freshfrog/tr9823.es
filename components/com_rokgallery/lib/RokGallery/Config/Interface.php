<?php
/**
  * @version   $Id: Interface.php 39200 2011-06-30 04:31:21Z btowles $
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

interface RokGallery_Config_Interface {

    /**
     * @abstract
     * @param $name
     * @return mixed the options value
     */
    public function getOption($name);
}
