<?php
/**
 * Controller for managing all database-backed settings.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppController', 'Controller');

/**
 * Settings Controller
 *
 * @package app.Controller
 */
class SettingsController extends AppController
{

	/**
	 * Helpers
	 *
	 * @var array
	 */
	public $helpers = array(
		'Form' => array('className' => 'SettingsForm'),
	);

	/**
	 * manage action
	 *
	 * @throws NotFoundException
	 * @return void
	 */
	public function manage()
	{
		// This needs auth restrictions if it is ever enabled in production,
		// but this can still be helpful during development.
		if(Configure::read('debug') < 2) {
			throw new NotFoundException();
		}

		$this->set('title_for_layout', __('Application Settings'));

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Setting->update($this->request->data['Setting'])) {
				$this->Session->setFlash(
					__('All settings have been saved.'), 'alert-success'
				);
				$this->Setting->load();
			} else {
				$this->Session->setFlash(
					__('Settings could not be saved.'), 'alert-error'
				);
			}
		}

		if (!$this->request->data) {
			$this->request->data = array('Setting' => Configure::read());
		}
	}

}
