<?php
/**
 * Contributors Controller
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppController', 'Controller');

/**
 * Contributors Controller
 *
 * @package app.Controller
 *
 * @property Contributor $Contributor
 */
class ContributorsController extends AppController
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
			'contributors' => $this->Paginator->paginate(),
			'_serialize' => array('contributors')
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
		$contributor = $this->Contributor->find('first', array(
			'contain' => array(
				'Plugin' => array(
					'Description',
					'PluginState' => array('State'),
					'Tag',
				),
			),
			'conditions' => array(
				'Contributor.name' => $name,
			),
		));
		if (!$contributor) {
			throw new NotFoundException(__('Invalid contributor'));
		}
		$this->set(array(
			'contributor' => $contributor,
			'_serialize' => array('contributor')
		));
	}

}