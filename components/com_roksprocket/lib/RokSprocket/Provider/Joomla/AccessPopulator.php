<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokSprocket_Provider_Joomla_AccessPopulator implements RokCommon_Filter_IPicklistPopulator
{
    /**
     *
     * @return array;
     */
    public function getPicklistOptions()
    {
        $options = array();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select('a.id AS value, a.title AS text');
        $query->from('#__viewlevels AS a');
        $query->group('a.id, a.title, a.ordering');
        $query->order('a.ordering ASC');
        $query->order($query->qn('title') . ' ASC');

        // Get the options.
        $db->setQuery($query);
        $items = $db->loadObjectList('value');


        // Check for a database error.
        if ($db->getErrorNum())
        {
            JError::raiseWarning(500, $db->getErrorMsg());
            return null;
        }

        foreach ($items as $item) {
            $options[$item->value] = $item->text;

        }
        return $options;
    }
}
