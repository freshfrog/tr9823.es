<?php
/**
 * @version   $Id: Joomla17.php 52337 2012-04-09 22:46:49Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_PlatformInfo_Joomla17 implements RokCommon_PlatformInfo
{
    /**
     * Returns the URL for a given file based on the full file path passed in
     * @param $filepath
     * @return string
     */
    public function getUrlForPath($filepath)
    {
        jimport('joomla.environment.uri');
        jimport('joomla.filesystem.path');
        $base = JURI::root(true);
        $file_real_path = JPath::clean($filepath,'/');
        $site_real_path = JPath::clean(JPATH_SITE,'/');
        $url_path = $base.str_replace($site_real_path,'',$file_real_path);
        return $url_path;
    }

    /**
     * @param bool $admin
     *
     * @return string the name of the current template
     */
    public function getDefaultTemplate($admin = false)
    {

        $app = JFactory::getApplication();
        if ($admin) {
            return $app->getTemplate();
        } else {
            // Load styles
            $db    = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('template');
            $query->from('#__template_styles as s');
            $query->where('s.client_id = 0');
            $query->where('s.home = 1');
            $db->setQuery($query);
            $template = $db->loadResult();
            return $template;
        }
    }

    /**
     * @param bool $admin
     *
     * @return string the path to the current template/theme root
     */
    public function getDefaultTemplatePath($admin = false)
    {
        $root = ($admin) ? JPATH_ADMINISTRATOR : JPATH_ROOT;
        return $root . '/templates/' . $this->getDefaultTemplate($admin);
    }

    /**
     * @return string the path to the current platform root
     */
    public function getRootPath()
    {
        return JPATH_ROOT;
    }

	public function getUrlBase()
	{
		return JURI::root(true);
	}


    /**
     * @param RokCommon_Service_Container $container
     */
    public function setPlatformParameters(RokCommon_Service_Container &$container)
    {
        $container['platform.name'] = 'joomla';
        $container['platform.displayname'] = 'Joomla';
        $container['platform.version'] = JVERSION;
        $container['platform.root'] = $this->getRootPath();
        $container['template.name'] = $this->getDefaultTemplate();
        $container['template.path'] = $this->getDefaultTemplatePath();
    }
}
