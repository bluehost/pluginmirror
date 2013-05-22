<?php
/**
 * The Plugins controller handles all plugin actions.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppController', 'Controller');

/**
 * Plugins Controller
 *
 * @package app.Controller
 *
 * @property Plugin $Plugin
 * @property PluginsState $PluginsState
 * @property State $State
 */
class PluginsController extends AppController
{

	public $uses = array('Plugin', 'PluginsState');

	public $components = array(
		'Paginator' => array(
			'contain' => array(
				'Contributor',
				'Description',
				'PluginsState' => array('State'),
				'Tag',
			),
			'order' => array(
				'Plugin.wp_updated' => 'desc'
			),
		),
	);

	/**
	 * index action
	 *
	 * @return void
	 */
	function index()
	{
		$this->set(array(
			'plugins' => $this->Paginator->paginate(),
			'_serialize' => array('plugins')
		));
	}

	/**
	 * view action
	 *
	 * @param string $slug
	 *
	 * @throws NotFoundException
	 * @return void
	 */
	function view($slug = null)
	{
		$plugin = $this->Plugin->find('first', array(
			'contain' => array(
				'Contributor',
				'Description',
				'PluginsState' => array('State'),
				'Tag',
			),
			'conditions' => array(
				'slug' => $slug,
			),
		));
		if (!$plugin) {
			throw new NotFoundException(__('Invalid plugin'));
		}
		$this->set(array(
			'title_for_layout' => $plugin['Plugin']['display_name'],
			'plugin' => $plugin,
			'_serialize' => array('plugin')
		));
	}

	/**
	 * mirror action
	 *
	 * @param null $slug
	 *
	 * @throws NotFoundException
	 * @return void
	 */
	function mirror($slug = null)
	{
		$this->PluginsState->recursive = -1;
		$cloning_count = $this->PluginsState->find('count', array(
			'conditions' => array(
				'PluginsState.ip_address' => $this->request->clientIp(false),
			),
		));

		if($cloning_count >= Configure::read('App.max_cloning_per_ip')) {
			$this->Session->setFlash(
				__('You have reached the maximum of %s plugins queued for cloning currently, please try again later.', Configure::read('App.max_cloning_per_ip')),
				'alert-warning', array('close' => true));
			$this->redirect($this->referer());
		}

		$plugin = $this->Plugin->find('first', array(
			'contain' => array(
				'PluginsState' => array('State'),
			),
			'conditions' => array(
				'slug' => $slug,
			),
		));

		// Some basic sanity checks...
		if (!$plugin) {
			throw new NotFoundException(__('Invalid plugin'));
		}
		if(in_array('cloned', Hash::extract($plugin, 'PluginsState.{n}.State.name'))) {
			$this->Session->setFlash(__('This plugin has already been cloned.'),
			                         'alert-error', array('close' => true));
			$this->redirect($this->referer());
		}
		if(in_array('cloning', Hash::extract($plugin, 'PluginsState.{n}.State.name'))) {
			$this->Session->setFlash(__('This plugin is already in the cloning queue.'),
			                         'alert-error', array('close' => true));
			$this->redirect($this->referer());
		}

		if($this->PluginsState->findOrCreate($plugin['Plugin']['id'], 'cloning',
		                                     $this->request->clientIp(false))) {
			$this->Session->setFlash(__('Plugin has been added to the queue to be cloned.'),
			                         'alert-success', array('close' => true));
			$this->redirect($this->referer());
		}

		$this->Session->setFlash(__('There was a problem adding the plugin to the cloning queue.'),
		                         'alert-error', array('close' => true));
		$this->redirect($this->referer());
	}

}
