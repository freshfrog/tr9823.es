<?php
/**
 * @version   $Id: Phpunit.php 49831 2012-02-29 17:54:19Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_PlatformInfo_Phpunit implements RokCommon_PlatformInfo
{
    /**
     * Returns the URL for a given file based on the full file path passed in
     * @param $filepath
     * @return string
     */
    public function getUrlForPath($filepath)
    {
        return 'file://'.$filepath;
    }

    /**
     * @param bool $admin
     *
     * @return string the name of the current template
     */
    public function getDefaultTemplate($admin = false)
    {
        return 'phpunit';
    }

    /**
     * @param bool $admin
     *
     * @return string the path to the current template/theme root
     */
    public function getDefaultTemplatePath($admin = false)
    {
        return '';
    }

    /**
     * @return string the path to the current platform root
     */
    public function getRootPath()
    {
        return '';
    }

    /**
     * @param RokCommon_Service_Container $container
     *
     * @throws RokCommon_Exception
     */
    public function setPlatformParameters(RokCommon_Service_Container &$container)
    {

        $container['platform.name']        = 'phpunit';
        $container['platform.displayname'] = 'PHPUnit';
        $container['platform.version']     = 0;
        $container['platform.root'] = $this->getRootPath();
        $container['template.name'] = $this->getDefaultTemplate();
        $container['template.path'] = $this->getDefaultTemplatePath();
    }



}
