<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('_JEXEC') or die('Restricted access');

require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');
jimport('joomla.utilities.date');

/**
 * @package RocketTheme
 * @subpackage rokfeaturetable
 */
class modRokFeatureTableHelper
{
	
	function loadScripts(&$params)
	{
		JHTML::_('behavior.mootools');
		$doc = &JFactory::getDocument();
		$doc->addScript(JURI::Root(true).'/modules/mod_rokfeaturetable/tmpl/js/rokfeaturetable.js');
		
		if ($params->get('builtin_css', 1)) $doc->addStyleSheet(JURI::Root(true).'/modules/mod_rokfeaturetable/tmpl/css/rokfeaturetable.css');
	}
	
	function getData(&$params) {
		
		$data = array();
	
		for ($x=1;$x<=6;$x++) {
			$col = $params->get('data_col'.$x);
			if ($col) {
				$lines = explode("\n",$col);
				foreach ($lines as $line) {
					$previous = isset($row) ? $row[0] : null;
					$row = explode("::",$line);
					if (isset($row[1])) {
						if (preg_match("/\-sub/", $row[0])) {
							$previous = str_replace("-sub", '', str_replace("-link", '', str_replace("-classes", '', $previous)));
							$prev = $data[$x][$previous];
							$cur_data = modRokFeatureTableHelper::getLineData($row[1]);
							$prev->sub = $cur_data->data;
						} elseif (preg_match("/\-link/", $row[0])) {
							$previous = str_replace("-sub", '', str_replace("-link", '', str_replace("-classes", '', $previous)));
							$prev = $data[$x][$previous];
							$cur_data = modRokFeatureTableHelper::getLineData($row[1]);
							$prev->link = $cur_data->data;
						} elseif (preg_match("/\-classes/", $row[0])) {
							$previous = str_replace("-sub", '', str_replace("-link", '', str_replace("-classes", '', $previous)));
							$prev = $data[$x][$previous];
							$cur_data = modRokFeatureTableHelper::getLineData($row[1]);
							$prev->classes = $cur_data->data;
						}
						else {
							$data[$x][$row[0]] = modRokFeatureTableHelper::getLineData($row[1]);							
						}
						
						$previous = str_replace("-sub", '', str_replace("-link", '', str_replace("-classes", '', $previous)));
					}
				}
			
			}
		}
		
		return ($data);
	}
	
	function getLineData($line) {
		$linebits = new stdclass;
		if (strpos($line,"|")!==false) {
			$bits = explode("|",$line);
			$linebits->style = $bits[0];
			$linebits->data = $bits[1];
		} else {
			$linebits->style = "";
			$linebits->data = $line;
		}
		return $linebits;
	}
	
	public static function _getJSVersion() {

        return "";
    }
}
