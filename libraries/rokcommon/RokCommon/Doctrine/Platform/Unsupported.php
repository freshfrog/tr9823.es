<?php
 /**
  * @version   $Id: Unsupported.php 48519 2012-02-03 23:18:52Z btowles $
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

class RokCommon_Doctrine_Platform_Unsupported implements RokCommon_Doctrine_Platform
{
    /**
     * @return string a doctrine safe tablename format
     */
    public function getTableNameFormat()
    {
        // TODO: Implement getTableNameFormat() method.
        throw new RokCommon_Exception('Unimplmented function getTableNameFormat()');

    }

    /**
     * @return string a doctrine safe connection URL
     */
    public function getConnectionUrl()
    {
        // TODO: Implement getTableNameFormat() method.
        throw new RokCommon_Exception('Unimplmented function getConnectionUrl()');
    }

    /**
     * @param string $tablename
     * @return string
     */
    public function setTableName($tablename)
    {
        return $tablename;
    }

    /**
     * @return string the schema name for the platform
     */
    public function getSchema()
    {
        // TODO: Implement getSchema() method.
        throw new RokCommon_Exception('Unimplmented function getSchema()');
    }

    /**
     * @return string the database username for the platform
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        throw new RokCommon_Exception('Unimplmented function getUsername()');
    }

    /**
     * @return string the database password for the platform
     */
    public function getPassword()
    {
        // TODO: Implement getPassword() method.
        throw new RokCommon_Exception('Unimplmented function getPassword()');
    }

    /**
     * @return string the database hostname for the platform
     */
    public function getHost()
    {
        // TODO: Implement getHost() method.
        throw new RokCommon_Exception('Unimplmented function getHost()');
    }


}
