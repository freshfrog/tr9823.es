<?php
/**
 * @version   3.2.19 April 2, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class GantrySuckerfishTheme extends AbstractRokMenuTheme {

    protected $defaults = array(
        'class_sfx' => ''
    );

    public function getFormatter($args){
        require_once(dirname(__FILE__).'/formatter.php');
        return new GantrySuckerfishFormatter($args);
    }

    public function getLayout($args){
        require_once(dirname(__FILE__).'/layout.php');
        return new GantrySuckerfishLayout($args);
    }
}
