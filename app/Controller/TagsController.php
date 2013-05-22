<?php
/**
 * Tags Controller
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppController', 'Controller');

/**
 * Tags Controller
 *
 * @package app.Controller
 *
 * @property Tag $Tag
 */
class TagsController extends AppController
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
			'tags' => $this->Paginator->paginate(),
			'_serialize' => array('tags')
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
		$tag = $this->Tag->find('first', array(
			'contain' => array(
				'Plugin' => array(
					// With thousands of plugins, this needs to be conservative.
					'fields' => array('id', 'slug', 'display_name', 'wp_updated'),
				),
			),
			'conditions' => array(
				'Tag.name' => $name,
			),
		));
		if (!$tag) {
			throw new NotFoundException(__('Invalid tag'));
		}
		$this->set(array(
			'tag' => $tag,
			'_serialize' => array('tag')
		));
	}

}