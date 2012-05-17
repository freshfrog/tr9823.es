<?php
 /**
  * @version   $Id: Table.php 48519 2012-02-03 23:18:52Z btowles $
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

abstract class RokCommon_Doctrine_Table extends Doctrine_Table
{
    /**
     * @param $tableName
     * @internal param void $
     */
    public function setTableName($tableName)
    {
        parent::setTableName(RokCommon_Doctrine::getPlatformInstance()->setTableName($tableName));
    }

}
