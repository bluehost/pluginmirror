<?php
/**
 * The Descriptions controller.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppController', 'Controller');

/**
 * Descriptions Controller
 *
 * @package app.Controller
 *
 * @property Description $Description
 */
class DescriptionsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
		'Paginator' => array(
			'contain' => array('Plugin'),
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
			'descriptions' => $this->Paginator->paginate(),
			'_serialize' => array('descriptions')
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
		$description = $this->Description->find('first', array(
			'contain' => array(
				'Plugin' => array(
					'Contributor',
					'PluginsState' => array('State'),
					'Tag',
				),
			),
			'conditions' => array(
				'Description.id' => $id,
			),
		));
		if (!$description) {
			throw new NotFoundException(__('Invalid description'));
		}
		$this->set(array(
			'description' => $description,
			'_serialize' => array('description')
		));
	}

}