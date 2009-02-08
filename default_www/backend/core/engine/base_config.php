<?php

/**
 * BackendBaseConfig
 *
 * This is the base-object for config-files. The module-specific config-files can extend the functionality from this class
 *
 * @package		backend
 * @subpackage	core
 *
 * @author 		Tijs Verkoyen <tijs@netlash.com>
 * @since		2.0
 */
class BackendBaseConfig
{
	/**
	 * The default action
	 *
	 * @var	string
	 */
	protected $defaultAction = 'index';


	/**
	 * The disabled actions
	 *
	 * @var	array
	 */
	protected $disabledActions = array();


	/**
	 * The disabled AJAX-actions
	 *
	 * @var	array
	 */
	protected $disabledAJAXActions = array();


	/**
	 * All the possible actions
	 *
	 * @var	array
	 */
	protected $possibleActions = array();



	/**
	 * All the possible AJAX actions
	 *
	 * @var	array
	 */
	protected $possibleAJAXActions = array();


	/**
	 * The linked actions
	 *
	 * @var	array
	 */
	protected $linkedAjaxActions = array();


	/**
	 * Default constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		// read the possible actions based on the files
		$this->setPossibleActions();
	}


	public function getLinkedAction($ajaxAction)
	{
		// redefine
		$ajaxAction = (string) $ajaxAction;

		// validate AJAX-action
		if(!in_array($ajaxAction, $this->getPossibleAJAXActions())) throw new BackendException('Invalid AJAX-action ('. $ajaxAction .').');

		// .....
		if(isset($this->linkedAjaxActions[$ajaxAction])) return $this->linkedAjaxActions[$ajaxAction];

		// fallback
		return false;
	}


	/**
	 * Get the possible actions
	 *
	 * @return	array
	 */
	public function getPossibleActions()
	{
		return $this->possibleActions;
	}


	/**
	 * Get the possible AJAX actions
	 *
	 * @return	array
	 */
	public function getPossibleAJAXActions()
	{
		return $this->possibleAJAXActions;
	}


	/**
	 * Set the possible actions, based on files in folder
	 * You can disable action in the config file. (Populate $disabledActions)
	 *
	 * @return	void
	 */
	protected function setPossibleActions()
	{
		// get filelist (only those with .php-extension)
		$aActionFiles = (array) SpoonFile::getList(BACKEND_MODULE_PATH .'/actions', '/(.*).php/');

		// loop filelist
		foreach ($aActionFiles as $file)
		{
			// get action by removing the extension, actions should not contain spaces (use _ instead)
			$action = strtolower(str_replace('.php', '', $file));

			// if the action isn't disabled add it to the possible actions
			if(!in_array($action, $this->disabledActions)) $this->possibleActions[$file] = $action;
		}

		// get filelist (only those with .php-extension)
		$aAJAXActionFiles = (array) SpoonFile::getList(BACKEND_MODULE_PATH .'/ajax', '/(.*).php/');

		// loop filelist
		foreach ($aAJAXActionFiles as $file)
		{
			// get action by removing the extension, actions should not contain spaces (use _ instead)
			$action = strtolower(str_replace('.php', '', $file));

			// if the action isn't disabled add it to the possible actions
			if(!in_array($action, $this->disabledAJAXActions)) $this->possibleAJAXActions[$file] = $action;
		}
	}
}

?>