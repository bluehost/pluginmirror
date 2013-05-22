<?php
/**
 * CakePHP console shell for adding new plugins on WordPress.org.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Console.Command
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppShell', 'Console/Command');

/**
 * CakePHP console shell for adding new plugins on WordPress.org.
 *
 * @package app.Console.Command
 *
 * @property Plugin $Plugin
 * @property State $State
 */
class CheckShell extends AppShell {

	public $uses = array(
		'Plugin',
		'State',
	);

	public $locked_commands = array('main');

	/**
	 * Gets the option parser instance and configures it.
	 *
	 * @return ConsoleOptionParser
	 */
	function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(__('Adds any new plugins on WordPress.org, and queues them up for updates.'));
		return $parser;
	}

	/**
	 * Adds any new plugins on WordPress.org, and queues them up for updates.
	 *
	 * @return int Shell return code.
	 */
	function main()
	{
		$this->out(__('Fetching list of plugins from SVN repository...'));

		$repo_url = Configure::read('App.svn_url');
		$command = "svn list $repo_url | sed 's/\\/$//'";
		$results = shell_exec($command);
		if(is_null($results)) {
			$this->_unlock();
			$this->error(__('Error'), __('Failed to fetch list of plugins using SVN.'));
			return 1;
		}
		$plugins = explode("\n", $results);

		$this->out(__('Looking for new plugins that have not been added yet...'));

		$plugins = array_filter($plugins, function($value) {
			return preg_match('/^[-0-9a-z_]+$/i', $value);
		});
		$plugins = array_diff($plugins, $this->Plugin->find('list', array('fields' => array('slug'))));
		if(empty($plugins)) {
			$this->out(__('<info>No new plugins to add.</info>'));
			$this->_unlock();
			return 0;
		}
		// TODO: Remove once live, or testing repo is in place.
		$plugins = array_slice($plugins, 0, 1000);

		$this->out(__('Saving %d new plugins...', count($plugins)));

		$plugins = Hash::map($plugins, '{n}', array($this, '_newPluginMap'));

		if($this->Plugin->saveAll($plugins, array('deep' => true))) {
			$this->out(__('<info>All new plugins added successfully.</info>'));
			$this->_unlock();
			return 0;
		}
		$this->_unlock();
		$this->error(__('Database Error'), __('Failed adding %d new plugins to the database.', count($plugins)));
		return 1;
	}

	/**
	 * Helper for mapping new plugin default settings.
	 */
	function _newPluginMap($value)
	{
		return array(
			'Plugin' => array(
				'slug' => $value,
			),
			'PluginsState' => array(
				array(
					'PluginsState' => array(
						'state_id' => $this->State->getIdByName('refreshing'),
					),
				),
			),
		);
	}

}