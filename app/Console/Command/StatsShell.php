<?php
/**
 * CakePHP console shell for updating latest stats.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Console.Command
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppShell', 'Console/Command');

/**
 * CakePHP console shell for updating latest stats.
 *
 * @package app.Console.Command
 *
 * @property Plugin $Plugin
 * @property State $State
 * @property Stat $Stat
 */
class StatsShell extends AppShell {

	public $uses = array(
		'Plugin',
		'State',
		'Stat',
	);

	public $locked_commands = array('main');

	/**
	 * Gets the option parser instance and configures it.
	 *
	 * @return ConsoleOptionParser
	 */
	function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(__('Updates the current iteration of stats.'));
		return $parser;
	}

	/**
	 * Updates the current iteration of stats.
	 *
	 * @return int Shell return code.
	 */
	function main()
	{
		$this->out(__('Gathering plugin and work queue statistics...'));

		$plugin_count = $this->Plugin->find('count');

		$this->State->recursive = -1;
		$states = $this->State->find('all', array(
			'joins' => array(
				array(
					'table' => 'plugins_states',
					'alias' => 'PluginsState',
					'type' => 'LEFT',
					'conditions' => array('PluginsState.state_id = State.id'),
				),
			),
			'fields' => array(
				'State.name', 'COUNT(PluginsState.id) as count'
			),
			'group' => 'State.id',
		));

		$state_counts = Hash::combine($states, '{n}.State.name', '{n}.0.count');

		$stat = $this->Stat->save(array(
			'total'      => $plugin_count,
			'cloned'     => $state_counts['cloned'],
			'cloning'    => $state_counts['cloning'],
			'refreshing' => $state_counts['refreshing'],
			'removed'    => $state_counts['removed'],
			'updating'   => $state_counts['updating'],
		));

		if(!$stat) {
			$this->_unlock();
			$this->error(__('Database Error'), __('Failed saving plugin statistics to the database.'));
		}

		$this->out(__('<info>Saved statistics successfully.</info>'));
		$this->_unlock();
		return 0;
	}

}