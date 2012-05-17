<?php
/**
 * @version   $Id: Wordpress.php 51465 2012-03-27 18:15:00Z steph $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_PlatformInfo_Wordpress implements RokCommon_PlatformInfo
{
    /**
     * Returns the URL for a given file based on the full file path passed in
     * @param $filepath
     * @return string
     */
    public function getUrlForPath($filepath)
    {
        $base = WP_CONTENT_URL;
        $file_real_path = self::clean($filepath,'/');
        $site_real_path = self::clean(WP_CONTENT_DIR,'/');
        $url_path = $base.str_replace($site_real_path,'',$file_real_path);
        return $url_path;
    }

    protected function clean($path, $ds=DS)
    {
        $path = trim($path);
        if (empty($path)) {
                $path = WP_CONTENT_DIR;
        } else {
                // Remove double slashes and backslahses and convert all slashes and backslashes to DS
                $path = preg_replace('#[/\\\\]+#', $ds, $path);
        }
        return $path;
    }

    /**
     * @param bool $admin
     *
     * @return string the name of the current template
     */
    public function getDefaultTemplate($admin = false)
    {
        return get_current_theme();
    }

    /**
     * @param bool $admin
     *
     * @return string the path to the current template/theme root
     */
    public function getDefaultTemplatePath($admin = false)
    {
        return get_template_directory();
    }

    /**
     * @return string the path to the current platform root
     */
    public function getRootPath()
    {
        return ABSPATH;
    }

	public function getUrlBase()
	{
		return false;
	}


    /**
     * @param RokCommon_Service_Container $container
     */
    public function setPlatformParameters(RokCommon_Service_Container &$container){

        $container['platform.name'] = 'wordpress';
        $container['platform.displayname'] = 'Wordpress';
        $container['platform.version'] = get_bloginfo('version');
        $container['platform.root'] = $this->getRootPath();
        $container['template.name'] = $this->getDefaultTemplate();
        $container['template.path'] = $this->getDefaultTemplatePath();

    }
}
