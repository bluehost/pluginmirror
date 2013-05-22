<?php
/**
 * The States controller handles all state actions.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppController', 'Controller');

/**
 * States Controller
 *
 * @package app.Controller
 *
 * @property State $State
 */
class StatesController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
		'Paginator' => array(
			'contain' => array(),
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
			'states' => $this->Paginator->paginate(),
			'_serialize' => array('states')
		));
	}

	/**
	 * view action
	 *
	 * @throws NotFoundException
	 * @param string $name
	 * @return void
	 */
	public function view($name = null)
	{
		$state = $this->State->find('first', array(
			'contain' => array(
				'PluginsState' => array('Plugin'),
			),
			'conditions' => array(
				'State.name' => $name,
			),
		));
		if (!$state) {
			throw new NotFoundException(__('Invalid state'));
		}
		$this->set(array(
			'state' => $state,
			'_serialize' => array('state')
		));
	}

}