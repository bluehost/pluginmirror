<?php
/**
 * CakePHP console shell for scanning SVN, and marking plugins as dirty.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Console.Command
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppShell', 'Console/Command');

/**
 * CakePHP console shell for scanning SVN, and marking plugins as dirty.
 *
 * @package app.Console.Command
 *
 * @property Plugin $Plugin
 * @property PluginsState $PluginsState
 * @property Setting $Setting
 */
class ScanShell extends AppShell {

	public $uses = array(
		'Plugin',
		'PluginsState',
		'Setting'
	);

	public $locked_commands = array('main');

	/**
	 * Gets the option parser instance and configures it.
	 *
	 * @return ConsoleOptionParser
	 */
	function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(__('Scans SVN, marking changed plugins as dirty.'));
		$parser->addArgument('max', array('help' => __('Maximum number of revisions to check.')));
		return $parser;
	}

	/**
	 * Scans SVN for changes, and queues those plugins for update and refresh.
	 *
	 * @return int Shell return code.
	 */
	function main()
	{
		$max = 100;
		if(isset($this->args[0]) && is_numeric($this->args[0]) && $this->args[0] > 0) {
			$max = (int) $this->args[0];
		}

		$this->out(__('Checking latest revision from SVN...'));
		$latest_revision = $this->_getLatestRevision();

		if(!Configure::check('App.svn_revision')) {
			$this->out(__('<info>First update detected, skipping to revision %d.</info>', $latest_revision));
			$this->Setting->write('App.svn_revision', $latest_revision);
			$this->_unlock();
			return 0;
		}

		$revision = Configure::read('App.svn_revision');
		$last_revision = $revision + $max;
		if($last_revision > $latest_revision) {
			$last_revision = $latest_revision;
		}

		if($revision == $latest_revision) {
			$this->out(__('<info>No new changes, finished scanning.</info>'));
			$this->_unlock();
			return 0;
		}

		$this->out(__('Scanning SVN revisions %d through %d...',
		              $revision, $last_revision));

		$plugin_slugs = $this->_getModifiedPlugins($revision, $last_revision);

		$this->_refreshPlugins($plugin_slugs);
		$this->_updatePlugins($plugin_slugs);

		$this->out(__('<info>Finished scanning.</info>'));
		$this->Setting->write('App.svn_revision', $last_revision);
		$this->_unlock();
		return 0;
	}

	/**
	 * Fetches the latest revision in the SVN repository.
	 *
	 * @return int
	 */
	protected function _getLatestRevision()
	{
		$revision = 0;

		try
		{
			$xml_info = $this->_exec(
				'svn info --xml %s', Configure::read('App.svn_url')
			);
			$data = Xml::build(implode('', $xml_info));
			$revision = (int) $data->entry['revision'];
		}
		catch(Exception $e)
		{
			$this->_unlock();
			$this->error(__('Error'), __('Failed fetching the latest revision.'));
		}

		return $revision;
	}

	/**
	 * Scans summarized diff from SVN for all modified plugins.
	 *
	 * @param int $first_revision Revision to start scanning from.
	 * @param int $last_revision Last revision in diff range.
	 *
	 * @return array Array of all modified plugin slugs.
	 */
	protected function _getModifiedPlugins($first_revision, $last_revision)
	{
		$plugins = array();

		try
		{
			$xml_info = $this->_exec(
				'svn diff --summarize --xml --revision %s %s',
				sprintf('%d:%d', $first_revision, $last_revision),
				Configure::read('App.svn_url')
			);
			$data = Xml::build(implode('', $xml_info));
			foreach($data->paths->path as $path) {
				$path = substr($path, strlen(Configure::read('App.svn_url')) + 1);
				$parts = preg_split('/[\/]/', $path, 2, PREG_SPLIT_NO_EMPTY);
				$plugins[] = $parts[0];
			}
			$plugins = array_unique($plugins);
		}
		catch(Exception $e)
		{
			$this->_unlock();
			$this->error(__('Error'), __('Failed fetching the latest revision.'));
		}

		return $plugins;
	}

	/**
	 * Queues any plugins with the given slugs for a refresh.
	 *
	 * @param array $plugin_slugs Array of plugin slugs to refresh.
	 *
	 * @return void
	 */
	protected function _refreshPlugins($plugin_slugs)
	{
		$this->Plugin->recursive = -1;
		$plugins = $this->Plugin->find('all', array(
			'conditions' => array(
				'Plugin.slug' => $plugin_slugs,
			),
		));

		if(count($plugins) == 0) {
			$this->out(__('No plugins need to be refreshed.'));
			return;
		}

		$this->out(__('Marking %d plugins for refresh...', count($plugins)));

		foreach($plugins as $plugin) {
			$this->PluginsState->findOrCreate(
				$plugin['Plugin']['id'], 'refreshing');
		}

		return;
	}

	/**
	 * Queues any cloned plugins with the given slugs for an update.
	 *
	 * @param array $plugin_slugs Array of plugin slugs to update.
	 *
	 * @return void
	 */
	protected function _updatePlugins($plugin_slugs)
	{
		$cloned_plugins = $this->Plugin->findByState('cloned', array(
			'conditions' => array(
				'Plugin.slug' => $plugin_slugs,
			),
		));

		if(count($cloned_plugins) == 0) {
			$this->out(__('No cloned plugins need to be updated.'));
			return;
		}

		$this->out(__('Marking %d cloned plugins for update...', count($cloned_plugins)));

		foreach($cloned_plugins as $plugin) {
			$this->PluginsState->findOrCreate(
				$plugin['Plugin']['id'], 'updating');
		}

		return;
	}

}