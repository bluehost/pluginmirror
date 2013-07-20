<?php
/**
 * The PluginsState model represents individual plugin states.
 *
 * This is mostly used to keep track of the time that a plugin has been in specific states.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppModel', 'Model');

/**
 * PluginsState Model
 *
 * @package app.Model
 *
 * @property Plugin $Plugin
 * @property State $State
 */
class PluginsState extends AppModel
{

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array('Plugin', 'State');

	/**
	 * Bumps the modified date for the given plugin state.
	 *
	 * This is useful to push a plugin to the back of the line in work queues
	 * in case of some temporary failure.
	 *
	 * @param $id
	 *
	 * @return mixed
	 */
	public function touch($id)
	{
		$this->create(array('id' => $id));
		return $this->save();
	}

	/**
	 * Returns the given PluginsState, creating it if it does not exist.
	 *
	 * @param integer $plugin_id
	 * @param string $state_name
	 * @param string $ip_address
	 *
	 * @return array|bool
	 */
	public function findOrCreate($plugin_id, $state_name, $ip_address = null)
	{
		$state_id = $this->State->getIdByName($state_name);

		if($state_id === false) {
			return false;
		}

		$this->recursive = -1;
		$plugin_state = $this->find('first', array(
			'conditions' => array(
				'PluginsState.plugin_id' => $plugin_id,
				'PluginsState.state_id' => $state_id,
			)
		));

		if(empty($plugin_state)) {
			$this->create(array(
				'plugin_id' => $plugin_id,
				'state_id' => $state_id,
			));
			if(!is_null($ip_address)) {
				$this->set('ip_address', $ip_address);
			}
			return $this->save();
		}

		return $plugin_state;
	}

	/**
	 * Returns the number of plugins with the given state.
	 *
	 * @param string $state_name Name of the state.
	 *
	 * @return int
	 */
	public function count($state_name)
	{
		$this->recursive = -1;
		$count = $this->find('count', array(
			'conditions' => array(
				'state_id' => $this->State->getIdByName($state_name),
			),
		));

		return $count;
	}

}