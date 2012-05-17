<?php
/**
 * @version   $Id$
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 */
class plgSystemRokSprocket extends JPlugin
{

	const NEEDED_ROKCOMMON_VERSION = '2.6';
	/**
	 *
	 */
	const MODULE_NAME = 'mod_roksprocket';
	/**
	 *
	 */
	const COMPONENT_NAME = 'com_roksprocket';

	/**
	 * @return mixed
	 */
	public function onAfterInitialise()
	{

		$app = JFactory::getApplication();
		$db  = JFactory::getDBO();

		if (!defined('ROKCOMMON') || !defined('ROKCOMMON_PLUGIN_LOADED')) {
			$error_string = 'RokSprocket needs the RokCommon Library and Plug-in installed and enabled. The RokCommon System Plug-in needs to be before the RokSprocket System Plug-in in the Plug-in Manager';
		} else if (!preg_match('/project.version/', ROKCOMMON) && version_compare(preg_replace('/-SNAPSHOT/', '', ROKCOMMON), self::NEEDED_ROKCOMMON_VERSION, '<')) {
			$error_string = sprintf('RokSprocket needs at least RokCommon Version %s.  You currently have RokCommon Version %s', self::NEEDED_ROKCOMMON_VERSION, ROKCOMMON);
		}


		// check for newer rokcommons version
		/** @var $extensiontable JTableExtension */
		$extensiontable = JTable::getInstance('Extension');
		$ext_id         = $extensiontable->find(array(
		                                             'type'   => 'component',
		                                             'element'=> 'com_roksprocket'
		                                        ));

		if ($ext_id == null) {
			$error_string = 'The RokSprocket Module needs the RokSprocket Component installed.';
		} else {
			$extensiontable->load($ext_id);
			if (!(bool)$extensiontable->enabled) {
				$error_string = 'The RokSprocket Module needs the RokSprocket Component enabled.';
			}
		}

		if (!empty($error_string)) {
			if (JError::$legacy) {
				return JError::raiseWarning(500, $error_string);
			} else {
				throw new Exception($error_string);
			}
		}

		$mod_id = $extensiontable->find(array(
		                                     'type'   => 'module',
		                                     'element'=> 'mod_roksprocket'
		                                ));
		if ($ext_id == null) {
			return;
		} else {
			$extensiontable->load($ext_id);
			if (!(bool)$extensiontable->enabled) {
				return;
			}
		}

		define('ROKSPROCKET', '1.5');
		define('ROKSPROCKET_VERSION', '1.5');

		$container = RokCommon_Service::getContainer();

		// load each providers container file
		$this->loadProviders($container);
		$this->loadLayouts($container);
		$this->loadAddons($container);

		if (!$app->isAdmin()) return;

		if (array_key_exists('option', $_REQUEST) && array_key_exists('task', $_REQUEST)) {
			$input  = $app->input;
			$option = $input->get('option', null, 'WORD');
			$task   = $input->get('task', null, 'CMD');
			$eid    = $input->get('eid', null, 'int');

			if ($option == 'com_modules' || $option == 'com_advancedmodules') {
				switch ($task) {
					case 'module.add':
						$extensionId = (int)$app->getUserState('com_modules.add.module.extension_id', null);
						if (null == $extensionId && $eid != null) {
							$extensionId = $eid;
						}
						$query = $db->getQuery(true);
						$query->select('element, client_id');
						$query->from('#__extensions');
						$query->where('extension_id = ' . $extensionId);
						$query->where('type = ' . $db->quote('module'));
						$query->where('element = ' . $db->quote(self::MODULE_NAME));
						$db->setQuery($query);
						$extension = $db->loadObject();
						if (!empty($extension)) {
							$input->set('option', self::COMPONENT_NAME);
							JRequest::setVar('option', self::COMPONENT_NAME, 'GET', true);
						}
						break;
					case 'module.edit':
						$module_id = $input->get('id', null, 'int');
						if ($this->isRokSprocketModule($module_id)) {
							$input->set('option', self::COMPONENT_NAME);
							JRequest::setVar('option', self::COMPONENT_NAME, 'GET', true);
						}
						break;
					case 'modules.duplicate':
						$input->set('option', self::COMPONENT_NAME);
						JRequest::setVar('option', self::COMPONENT_NAME, 'GET', true);
						$input->set('task','module.duplicate');
						JRequest::setVar('task', 'module.duplicate', 'POST', true);
						break;
				}
				$session = JFactory::getSession();
				$session->set('com_roksprocket.redirected.from', $option);
			}
		}
	}

	/**
	 * @param RokCommon_Service_Container $container
	 */
	protected function loadProviders(RokCommon_Service_Container &$container)
	{
		RokCommon_Composite::addPackagePath('roksprocket_providers', $container['roksprocket.providers.path']);
		$context        = RokCommon_Composite::get('roksprocket_providers');
		$priority_files = $context->getAllSubFiles($container['roksprocket.providers.file']);
		foreach ($priority_files as $priority => $files) {
			foreach ($files as $file) {
				RokCommon_Service::addConfigFile($file);
			}
		}
	}

	/**
	 * @param RokCommon_Service_Container $container
	 */
	protected function loadLayouts(RokCommon_Service_Container &$container)
	{
		/** @var $platforminfo RokCommon_PlatformInfo */
		$platforminfo = $container->platforminfo;

		RokCommon_Composite::addPackagePath('roksprocket_layouts', JPATH_SITE . '/components/com_roksprocket/layouts', 10);
		RokCommon_Composite::addPackagePath('roksprocket_layouts', $container['roksprocket.template.override.path'] . '/layouts', 20);
		JForm::addFieldPath($container['template.path'] . '/admin/forms/fields');

		$context        = RokCommon_Composite::get('roksprocket_layouts');
		$priority_files = $context->getAllSubFiles('meta.xml');
		ksort($priority_files, true);
		foreach ($priority_files as $priority => $files) {
			foreach ($files as $file) {
				RokCommon_Service::addConfigFile($file);
			}
		}
	}

	protected function loadAddons(RokCommon_Service_Container &$container)
	{
		foreach ($container['roksprocket.addons'] as $service) {
			$instance = $container->$service;
		}
	}


	/**
	 * @param $id
	 *
	 * @return bool
	 */
	protected function isRokSprocketModule($id)
	{
		/** @var $table JTableModule */
		$table = JTable::getInstance('Module', 'JTable', array());
		if (!$table->load($id) || $table->get('module') !== self::MODULE_NAME) {
			return false;
		}
		return true;
	}

	/**
	 * @return mixed
	 */
	public function onBeforeRender()
	{
		if (!defined('ROKSPROCKET')) return;
		$app      = JFactory::getApplication();
		$document = JFactory::getDocument();
		$doctype  = $document->getType();
		if (!$app->isAdmin()) return;

		$option = JRequest::getString('option', '');
		$view   = JRequest::getString('view', '');

		if ($doctype == 'html' && ($option == 'com_modules' || $option == 'com_advancedmodules') && (($view == 'modules') || empty($view))) {
			$css = "
                .sprocket > a {vertical-align:middle;}
                .badge {margin:1px 5px;color:#fff;padding:2px 6px;font-family:Helvetica,Arial,sans-serif;border-radius:4px;display:inline-block;vertical-align:middle;background-color: #666;}
                .provider {display:inline-block;width:16px;height:16px;vertical-align:middle;}
                span.amm-no-modal {padding-left: 19px;}
            ";
			$document->addStyleDeclaration($css);
		}
	}

	/**
	 * @return mixed
	 */
	public function onAfterRender()
	{
		if (!defined('ROKSPROCKET')) return;
		$app = JFactory::getApplication();
		if (!$app->isAdmin()) return;

		$option = JRequest::getString('option', '');
		$view   = JRequest::getString('view', '');

		if (($option == 'com_modules' || $option == 'com_advancedmodules') && (($view == 'modules') || empty($view))) {

			$document = & JFactory::getDocument();
			$doctype  = $document->getType();
			if ($doctype == 'html') {
				$db    = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query->select('id, title, params');
				$query->from('#__modules');
				$query->where('module = ' . $db->quote(self::MODULE_NAME));
				$db->setQuery($query);
				$data = $db->loadObjectList();

				if (sizeof($data) > 0) {
					$body = JResponse::getBody();
					$pq   = phpQuery::newDocument($body);

					foreach ($data as $sprocket) {
						$params   = json_decode($sprocket->params);
						$layout   = $params->layout;
						$provider = $params->provider;
						pq('td > input[value=' . $sprocket->id . ']')->parent()->parent()->find('a[href*=module.edit]')->parent()->addClass("sprocket")->find("a:last")->after('<span class="badge">' . ucfirst($layout) . '</span><div style="background:url(' . JURI::base(true) . '/components/com_roksprocket/assets/providers/' . $provider . '.png)" class="provider"></div>');
						//pq('td.sprocket')->find('a[href*="option=com_advancedmodules"][class="modal"]')->remove();
						pq('td.sprocket')->find('span.sprocket')->empty()->addClass('amm-no-modal')->removeClass('hasTip');
					}
					$body = $pq->getDocument()->htmlOuter();
					JResponse::setBody($body);
				}
			}
		}
	}

	/**
	 * @param $form
	 * @param $data
	 *
	 * @return mixed
	 */
	public function onContentPrepareForm($form, $data)
	{
		if (!defined('ROKSPROCKET')) return;
		$app = JFactory::getApplication();
		if (!$app->isAdmin()) return;

		if (defined('ROKCOMMON')) {


			$jinput = JFactory::getApplication()->input;

			$option = $jinput->get('option', null, 'WORD');
			$layout = $jinput->get('layout', null, 'WORD');


			if (in_array($option, array(
			                           'com_roksprocket'
			                      )) && $layout == 'edit' && $this->getModuleType($data)
			) {
				$this->processModuleConfig($form, $data);
			}
		}
	}

	/**
	 * @param $form
	 * @param $data
	 *
	 * @return mixed
	 */
	protected function processModuleConfig($form, $data)
	{
		// check the module to see if it has a rokconfig.xml
		$module_type = $this->getModuleType($data);

		$container = RokCommon_Service::getContainer();
		$options   = new RokCommon_Options();

		$section = new RokCommon_Options_Section('roksprocket_module', 'module_config.xml');
		$section->addPath(JPATH_SITE . '/components/com_roksprocket/');
		$section->addPath($container['roksprocket.template.override.path']);
		$options->addSection($section);

		// load up the Providers
		foreach ($container['roksprocket.providers.registered'] as $type => $providerinfo) {
			$provider_class = $container[sprintf('roksprocket.providers.registered.%s.class', $type)];
			$available      = call_user_func(array($provider_class, 'isAvailable'));
			if ($available) {
				$section = new RokCommon_Options_Section('provider_' . $type, $providerinfo->optionfile);
				$section->addPath($providerinfo->path);
				$options->addSection($section);
			}
		}

		// load up the layouts
		foreach ($container['roksprocket.layouts'] as $type => $layoutinfo) {
			$section = new RokCommon_Options_Section('layout_' . $type, $layoutinfo->options->file);
			foreach ($layoutinfo->paths as $layoutpath) {
				$section->addPath($layoutpath);
			}
			$options->addSection($section);
		}

		$container = RokCommon_Service::getContainer();

		$rcform    = new RokCommon_Config_Form($form);
		$xml       = $options->getJoinedXml();
		$jxml      = new JXMLElement($xml->asXML());
		$fieldsets = $jxml->xpath('/config/fields[@name = "params"]/fieldset');
		foreach ($fieldsets as $fieldset) {
			$overwrite = ((string)$fieldset['overwrite'] == 'true') ? true : false;
			$rcform->load($fieldset, $overwrite, '/config');
		}
		JForm::addFieldPath(JPATH_SITE . '/components/com_roksprocket/fields');
	}

	/**
	 * @param $data
	 *
	 * @return string
	 */
	protected function getModuleType(&$data)
	{
		if (is_array($data) && isset($data['module'])) {
			return $data['module'];
		} elseif (is_array($data) && empty($data)) {
			$form = JRequest::getVar('jform');
			if (is_array($form) && array_key_exists('module', $form)) {
				return $form['module'];
			}
		}
		if (is_object($data) && method_exists($data, 'get')) {
			return $data->get('module');
		}
		return '';
	}

	public function onContentAfterDelete($context, $data)
	{
		if (!defined('ROKSPROCKET')) return;

		switch ($context) {
			case 'com_content.article':
				$provider = 'joomla';
				$id       = $data->id;
				break;
			case 'com_zoo.item':
				$provider = 'zoo';
				$id       = $data->id;
				break;
			default:
				return true;
		}

		$db = JFactory::getDBO();
		// delete old per module settings
		$query = $db->getQuery(true);
		$query->delete();
		$query->from('#__roksprocket_items');
		$query->where('provider = ' . $db->quote($provider));
		$query->where('provider_id = ' . $db->quote($id));
		$db->setQuery((string)$query);
		$db->query();

		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		return true;
	}
}

$app    = JFactory::getApplication();
$input  = $app->input;
$format = $input->get('format', null, 'word');
$option = $input->get('option', null, 'WORD');
if (!$app->isAdmin() && $format == 'raw' && $option == 'com_roksprocket') {
	jimport('joomla.application.module.helper');
	jimport('joomla.utilities.utility');

	if (!class_exists('JDocumentRaw')) {
		/**
		 * DocumentRAW class, provides an easy interface to parse and display raw output
		 *
		 * @package     Joomla.Platform
		 * @subpackage  Document
		 * @since       11.1
		 */
		class JDocumentRaw extends JDocument
		{
			/**
			 * Array of Header <link> tags
			 *
			 * @var    array
			 * @since  11.1
			 */
			public $_links = array();

			/**
			 * Array of custom tags
			 *
			 * @var    array
			 * @since  11.1
			 */
			public $_custom = array();

			/**
			 * Name of the template
			 *
			 * @var    string
			 * @since  11.1
			 */
			public $template = null;

			/**
			 * Base url
			 *
			 * @var    string
			 * @since  11.1
			 */
			public $baseurl = null;

			/**
			 * Array of template parameters
			 *
			 * @var    array
			 * @since  11.1
			 */
			public $params = null;

			/**
			 * File name
			 *
			 * @var    array
			 * @since  11.1
			 */
			public $_file = null;

			/**
			 * String holding parsed template
			 *
			 * @var    string
			 * @since  11.1
			 */
			protected $_template = '';

			/**
			 * Array of parsed template JDoc tags
			 *
			 * @var    array
			 * @since  11.1
			 */
			protected $_template_tags = array();

			/**
			 * Integer with caching setting
			 *
			 * @var    integer
			 * @since  11.1
			 */
			protected $_caching = null;


			/**
			 * Class constructor
			 *
			 * @param   array  $options  Associative array of options
			 *
			 * @since   11.1
			 */
			public function __construct($options = array())
			{
				parent::__construct($options);

				//set mime type
				$this->_mime = 'text/html';

				//set document type
				$this->_type = 'raw';
			}

			/**
			 * Get the HTML document head data
			 *
			 * @return  array  The document head data in array form
			 *
			 * @since   11.1
			 */
			public function getHeadData()
			{
				$data                = array();
				$data['title']       = $this->title;
				$data['description'] = $this->description;
				$data['link']        = $this->link;
				$data['metaTags']    = $this->_metaTags;
				$data['links']       = $this->_links;
				$data['styleSheets'] = $this->_styleSheets;
				$data['style']       = $this->_style;
				$data['scripts']     = $this->_scripts;
				$data['script']      = $this->_script;
				$data['custom']      = $this->_custom;
				return $data;
			}


			/**
			 * Render the document.
			 *
			 * @param   boolean  $cache   If true, cache the output
			 * @param   array    $params  Associative array of attributes
			 *
			 * @return  The rendered data
			 *
			 * @since   11.1
			 */
			public function render($cache = false, $params = array())
			{
				parent::render();
				return $this->getBuffer();
			}
		}
	}
}
