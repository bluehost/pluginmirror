<?php
/**
 * Base application controller.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('Controller', 'Controller');

/**
 * Base Application Controller
 *
 * @package app.Controller
 *
 * @property Setting $Setting
 */
class AppController extends Controller
{

	/**
	 * Models
	 *
	 * @var array
	 */
	public $uses = array(
		'Setting',
	);

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
		'DebugKit.Toolbar' => array(),
		'Paginator' => array(
			'paramType' => 'querystring',
			'limit' => 10,
		),
		'RequestHandler' => array(),
		'Security' => array(),
		'Session' => array(),
	);

	/**
	 * Helpers
	 *
	 * @var array
	 */
	public $helpers = array(
		'Form' => array('className' => 'AppForm'),
		'Html' => array('className' => 'AppHtml', 'configFile' => 'html_tags'),
		'Paginator' => array('className' => 'AppPaginator'),
		'Session',
	);

	/**
	 * beforeFilter method
	 *
	 * @return void
	 */
	public function beforeFilter()
	{
		$this->Setting->load();
	}

}