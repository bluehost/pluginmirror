<?php
/**
 * The State model represents the various states that a plugin could be in.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppModel', 'Model');

/**
 * State model represents the various states that a plugin could be in.
 *
 * Currently, the following states are used:
 *
 * - refreshing: The plugin metadata is queued to be refreshed with any new information in the
 *               WordPress.org plugin repository.
 * - removed:    The plugin has been removed from the WordPress.org plugin listing.
 * - cloning:    The plugin is queued for it's initial git-svn clone.
 * - cloned:     The plugin has been cloned and mirrored to GitHub.
 * - updating:   The plugin's git-svn clone is queued for updates with changes in Subversion.
 *
 * @package app.Model
 *
 * @property PluginsState $PluginsState
 */
class State extends AppModel
{

	public $displayField = 'name';

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'PluginsState' => array(
			'dependent' => true,
			'order' => 'PluginsState.modified',
		),
	);

	/**
	 * Finds cached State id by the given name.
	 *
	 * @param string $state_name Name of the state.
	 *
	 * @return bool
	 */
	public function getIdByName($state_name)
	{
		if (($states = Cache::read('states_by_name')) === false) {
			$this->recursive = -1;
			$states = $this->find('all');
			$states = Hash::combine($states, '{n}.State.name', '{n}.State.id');
			Cache::write('states_by_name', $states);
		}

		if(array_key_exists($state_name, $states)) {
			return $states[$state_name];
		}

		return false;
	}

}