<?php
/**
 * PluginsStates Controller
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppController', 'Controller');

/**
 * PluginsStates Controller
 *
 * @package app.Controller
 *
 * @property PluginsState $PluginsState
 */
class PluginsStatesController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
		'Paginator' => array(
			'contain' => array(
				'Plugin',
				'State',
			),
		),
	);

	/**
	 * index action
	 *
	 * @return void
	 */
	public function index()
	{
		$this->set(array(
			'pluginsStates' => $this->Paginator->paginate(),
			'_serialize' => array('pluginsStates')
		));
	}

	/**
	 * view action
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		$pluginState = $this->PluginsState->find('first', array(
			'contain' => array(
				'Plugin' => array('Contributor', 'Tag'),
				'State',
			),
			'conditions' => array(
				'PluginsState.id' => $id,
			),
		));
		if (!$pluginState) {
			throw new NotFoundException(__('Invalid plugin state'));
		}
		$this->set(array(
			'pluginsState' => $pluginState,
			'_serialize' => array('pluginsState')
		));
	}

}