<?php
/**
 * @version   $Id: rokinjectmodule.php 48519 2012-02-03 23:18:52Z rhuk $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 */
class plgContentRokInjectModule extends JPlugin
{

    /**
     * @var array
     */
    protected static $has_run = array();

    /**
     * @param $context
     * @param $article
     * @param $params
     * @param $limitstart
     */
    public function onContentPrepare($context, &$article, &$params, $limitstart)
    {

        $checksum = md5($context . $article->text . ($params ? $params->toString() : '') . $limitstart);
        if (!in_array($checksum, self::$has_run)) {
        	self::$has_run[] = $checksum;
        	// [module-28 style=xhtml|none] syntax for loading any module instance

        	// Don't run this plugin when the content is being indexed
            if ($context == 'com_finder.indexer') {
                return true;
            }

            $regex = '/\[module-(\d{1,})(.*)\]/i';
            $matches = array();
            preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

            foreach ($matches as $match){

                $module_id = $match[1];
                $match_params = $match[2];
                $module_params = array();

                if (isset($match_params)) {
                    $param_match = array();
                    preg_match_all('/((\w+)\=(\w+))/i', $match_params, $param_match, PREG_SET_ORDER);
                    foreach ($param_match as $pmatch) {
                        $module_params[$pmatch[2]] = $pmatch[3];
                    }
                }

                $module_output = $this->_load_module($module_id, $module_params);
                $article->text = preg_replace($regex, $module_output, $article->text, 1);
            }

        }

    }

    /**
    * @param $module_id
    * @param $params
    *
    * @return mixed
    */
    protected function _load_module($module_id, $params)
    {
        $db =& JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__modules WHERE id='$module_id' ");
        $module = $db->loadObject();
        return JModuleHelper::renderModule($module, $params);
    }
}
