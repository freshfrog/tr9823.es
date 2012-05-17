<?php
/**
 * @version $Id$
 * @author RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined('_JEXEC') or die( 'Restricted access' );

class RokGalleryHelper {

    public static function setupGalleryPicker(RokGalleryViewGalleryPicker &$picker_view = null)
    {

        if ($picker_view == null)
        {
            $picker_view = new stdClass();
        }

        JHTML::_('behavior.mootools');
        $option = JRequest::getCmd('option');
        $session =& JFactory::getSession();
        $document =& JFactory::getDocument();

        $picker_view->textarea = JRequest::getVar('textarea', false);
        $picker_view->inputfield = JRequest::getVar('inputfield', false);

        $picker_view->gallery_id = (int) JRequest::getVar('gallery_id', 0);
        $picker_view->file_id = (int) JRequest::getVar('file_id', 0);
        $picker_view->load_page = (int) JRequest::getVar('page', 1);

        $picker_view->model = new RokGallery_Admin_MainPage();
        $picker_view->current_page = 1;
        $picker_view->items_per_row = '';

        $picker_view->images = JURI::root(true) . '/administrator/components/' . $option . '/assets/images/';
        $picker_view->items_per_page = ($picker_view->load_page * $picker_view->items_per_page);

        if (!$picker_view->gallery_id) $picker_view->galleries = RokGallery_Model_GalleryTable::getAll();
        else $picker_view->galleries = RokGallery_Model_GalleryTable::getSingle($picker_view->gallery_id);
        if (!$picker_view->file_id) {
            $picker_view->files = $picker_view->model->getFiles($picker_view->current_page, $picker_view->items_per_page);
        }
        else {
            $picker_view->filter = json_decode('{"type":"id", "operator":"is", "query":'.$picker_view->file_id.'}');
            $picker_view->files = $picker_view->model->getFiles($picker_view->current_page, $picker_view->items_per_page, array($picker_view->filter))->getFirst();
        }

        $picker_view->pager = $picker_view->model->getPager($picker_view->current_page, $picker_view->items_per_page);
        $picker_view->next_page = $picker_view->current_page + 1;
        $picker_view->next_page = ($picker_view->current_page == $picker_view->pager->getLastPage()) ? false : $picker_view->next_page;


        $application = JURI::root(true) . '/administrator/components/' . $option . '/assets/application/';
        $picker_view->images_url = JURI::root(true) . '/administrator/components/' . $option . '/assets/images/';
        $picker_view->url = JURI::root(true) . '/administrator/index.php?option=com_rokgallery&task=ajax&format=raw'; // debug: &XDEBUG_SESSION_START=default
        $picker_view->modal_url = JURI::root(true) . '/administrator/index.php?option=com_rokgallery&view=gallerypicker&tmpl=component';
        if ($picker_view->textarea !== false) $picker_view->modal_url .= "&textarea=" . $picker_view->textarea;
        if ($picker_view->inputfield !== false) $picker_view->modal_url .= '&inputfield=' . $picker_view->inputfield;

        $picker_view->more_pages = ($picker_view->next_page == false) ? "false" : "true";
        
        $script = 'var RokGallerySettings = {
            application: "' . $application . '",
            images: "' . $picker_view->images_url . '",
            next_page: "' . $picker_view->next_page .'",
            more_pages: ' . $picker_view->more_pages. ',
            items_per_page: "' . $picker_view->items_per_page .'",
            total_items: ' . $picker_view->pager->getNumResults() . ',
            url: "' . $picker_view->url . '",
            modal_url: "'.$picker_view->modal_url.'",
            textarea: "'.$picker_view->textarea.'",
            inputfield: "'.$picker_view->inputfield.'",
            token: "' . JUtility::getToken() . '",
            session: {
                name: "' . $session->getName() . '",
                id: "' . $session->getId() . '"
            },
            order: ["order-created_at", "order-desc"]
        };';

        $base = str_replace('/administrator', '', JURI::base(true));
        $admin = $base.'/administrator';
        $com_admin = $admin.'/components/'.$option;

        //allowed stylesheets array
        $css_r = array();
        $css_r[] = $com_admin.'/templates/gallerypicker/gallerypicker.css?version="2.10"';
        //j1.7 specific        
        $css_r[] = 'templates/system/css/system.css';
        $css_r[] = 'media/system/css/adminlist.css';

        //allowed scripts array
        $js_r = array();
        $js_r[] = $com_admin.'/assets/js/mootools.js';
        $js_r[] = $com_admin.'/assets/js/modal-1.3.js';
        $js_r[] = $com_admin.'/assets/application/Scrollbar.js?version="2.10"';
        $js_r[] = $com_admin.'/assets/application/MainPage.js?version="2.10"';
        $js_r[] = $com_admin.'/templates/gallerypicker/gallerypicker.js?version="2.10"';

        //allowed style declarations
        $style_r = array();

        //allowed script declarations, just need first part
        $script_r = array();
        $script_r[] = $script;
        $script_r[] = "window.addEvent('domready', function(){ new GalleryPicker('rokgallerypicker', {url: RokGallerySettings.modal_url}); });";
        //$script_r[] = "var GantrySmartLoad = GantryBuildSpans = function(){}; var InputsExclusion = [];";

        //remove all styles and add needed ones
        $document->_styleSheets = array();
        foreach($css_r as $css){
            $document->addstyleSheet($css);
        }

        //remove all scripts and add needed ones
        $document->_scripts = array();
        foreach($js_r as $js){
            $document->addscript($js);
        }

        //remove unwanted style declarations
        $document->_style = array();
        foreach($style_r as $style){
            $document->addStyleDeclaration($style);
        }

        //remove unwanted script declarations
        $document->_script = array();
        foreach($script_r as $script){
            $document->addScriptDeclaration($script);
        }
    }
}