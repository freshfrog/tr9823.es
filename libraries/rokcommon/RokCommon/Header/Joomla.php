<?php
 /**
  * @version   $Id: Joomla.php 52337 2012-04-09 22:46:49Z btowles $
  * @author    RocketTheme http://www.rockettheme.com
  * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
  * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
  */

class RokCommon_Header_Joomla implements RokCommon_Header_Interface
{
    /**
     * @var \JDocument
     */
    protected $document;

    /**
     *
     */
    public function __construct()
    {
        $this->document = JFactory::getDocument();
    }

	/**
	 * @param       $file
	 * @param int   $priority
	 * @param array $browserspecific
	 */
    public function addScript($file)
    {
	    $path_parts = pathinfo($file);

        $this->document->addScript($file);
    }

    /**
     * @param $text
     */
    public function addInlineScript($text)
    {
        $this->document->addScriptDeclaration($text);
    }

	/**
	 * @param       $file
	 * @param int   $priority
	 * @param array $browserspecific
	 */
    public function addStyle($file)
    {
        $this->document->addStyleSheet($file);
    }

    /**
     * @param $text
     */
    public function addInlineStyle($text)
    {
        $this->document->addStyleDeclaration($text);
    }

}
