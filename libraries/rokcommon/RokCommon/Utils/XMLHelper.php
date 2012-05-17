<?php
/**
 * @version   $Id: XMLHelper.php 48519 2012-02-03 23:18:52Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class RokCommon_Utils_XMLHelper
{
    /**
     * Reads a XML file.
     *
     * @param   string   $data    Full path and file name.
     * @param   boolean  $isFile  true to load a file or false to load a string.
     *
     * @return  mixed    JXMLElement on success or false on error.
     * @since   11.1
     *
     * @todo    This may go in a separate class - error reporting may be improved.
     * @see     JXMLElement
     */
    public static function getXML($data, $isFile = true)
    {

        // Disable libxml errors and allow to fetch error information as needed
        libxml_use_internal_errors(true);

        if ($isFile) {
            // Try to load the XML file
            $xml = simplexml_load_file($data, 'RokCommon_XMLElement');
        } else {
            // Try to load the XML string
            $xml = simplexml_load_string($data, 'RokCommon_XMLElement');
        }

        // todo log or output an error if bad XML
        //    		if (empty($xml)) {
        //    			// There was an error
        //    			JError::raiseWarning(100, JText::_('JLIB_UTIL_ERROR_XML_LOAD'));
        //
        //    			if ($isFile) {
        //    				JError::raiseWarning(100, $data);
        //    			}
        //
        //    			foreach (libxml_get_errors() as $error)
        //    			{
        //    				JError::raiseWarning(100, 'XML: '.$error->message);
        //    			}
        //    		}

        return $xml;
    }
}
