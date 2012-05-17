<?php
/**
 * K2 Check, Custom Param
 *
 * @package RocketTheme
 * @subpackage rokstories.elements
 * @version   1.9 March 20, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */


// no direct access
defined('_JEXEC') or die();
jimport('joomla.html.html');
jimport('joomla.form.formfield');
JHTML::_('behavior.mootools');
/**
 * @package RocketTheme
 * @subpackage rokstories.elements
 */
class JFormFieldK2check extends JFormField
{
    protected $type = 'k2check';

    public function getInput()
    {
        if (defined('K2_CHECK')) return;
        $k2 = JPATH_SITE . DS . "components" . DS . "com_k2" . DS . "k2.php";

        if (!file_exists($k2)) {
            define('K2_CHECK', 0);
            $warning_style = "style='background: #FFF3A3;border: 1px solid #E7BD72;color: #B79000;display: block;padding: 8px 10px;'";
            return "<span $warning_style><strong>K2 Component</strong> Not Found. In order to use the <strong>K2 Content</strong> type, you will need to <a href=\"http://k2.joomlaworks.gr\" target=\"_blank\">download and install it</a>.</span>";
        } else {
            define('K2_CHECK', 1);
            $success_style = "style='background: #d2edc9;border: 1px solid #90e772;color: #2b7312;display: block;padding: 8px 10px;'";
            return "<span $success_style><strong>K2 Component</strong> has been found and is available to use.</span>";
        }
    }
}