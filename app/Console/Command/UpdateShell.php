<?php
/**
 * CakePHP console shell for updating git clones from SVN.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Console.Command
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppShell', 'Console/Command');

/**
 * CakePHP console shell for updating git clones from SVN.
 *
 * @package app.Console.Command
 *
 * @property Plugin $Plugin
 * @property PluginsState $PluginsState
 */
class UpdateShell extends AppShell {

	public $uses = array(
		'Plugin',
		'PluginsState',
	);

	public $locked_commands = array('main');

	/**
	 * Gets the option parser instance and configures it.
	 *
	 * @return ConsoleOptionParser
	 */
	function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(__('Updates the git clones from SVN.'));
		$parser->addArgument('max', array('help' => __('Maximum number of plugins to update.')));
		return $parser;
	}

	/**
	 * Updates plugin git repositories from SVN.
	 *
	 * If unspecified, a maximum of 5 plugins will be updated with one run.
	 *
	 * @return int Shell return code.
	 */
	function main()
	{
		$max = 5;
		if(isset($this->args[0]) && is_numeric($this->args[0]) && $this->args[0] > 0) {
			$max = (int) $this->args[0];
		}

		$plugins = $this->Plugin->findByState('updating', array(
			'contain' => array(
				'PluginsState' => array('State'),
			),
			'order' => array('InnerPluginsState.modified'),
			'limit' => $max,
		));

		if(count($plugins) == 0) {
			$this->out(__('<info>No plugins need to be updated.</info>'));
			$this->_unlock();
			return 0;
		}

		$this->out(__('Updating %d plugins...', count($plugins)));

		foreach($plugins as $plugin)
		{
			$this->out(__('Updating: "%s" (%d)',
			              $plugin['Plugin']['slug'],
			              $plugin['Plugin']['id']));

			$git_path = sprintf(Configure::read('App.plugin_repo_path'), $plugin['Plugin']['slug']);
			$log_path = TMP . 'logs' . DS . 'git' . DS . $plugin['Plugin']['slug'] . '.log';

			if(!chdir($git_path)) {
				$this->out(__('<warning>Failed reading the git clone path.</warning>'));
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
				continue;
			}

			try
			{
				$this->_exec('git svn fetch --quiet --quiet >> %s 2>&1', $log_path);
				$this->_exec('git svn rebase --quiet --quiet >> %s 2>&1', $log_path);

				$existing_tags = $this->_exec('git tag --list');
				$tags = $this->_exec(
					'git for-each-ref --format %s %s | cut --delimiter / --fields 3-',
					'%(refname:short)', 'refs/remotes/svn/tags'
				);
				$tags = array_diff($tags, $existing_tags);
				foreach($tags as $tag) {
					$this->_exec(
						'git tag %s %s >> %s 2>&1', $tag,
						sprintf('refs/remotes/svn/tags/%s', $tag), $log_path
					);
				}

				$branches = $this->_getBranches($plugin['Plugin']['slug']);
				foreach($branches as $branch) {
					$this->_exec(
						'git branch --force %s %s >> %s 2>&1', $branch,
						sprintf('refs/remotes/svn/%s', $branch), $log_path
					);
				}

				$this->_exec(
					'git push --mirror %s >> %s 2>&1',
					sprintf(Configure::read('App.plugin_git_url'), $plugin['Plugin']['slug']),
					$log_path
				);
			}
			catch(RuntimeException $e)
			{
				$this->out(__('<warning>Failed to update "%s", please check the git log file.</warning>',
				              $plugin['Plugin']['slug']));
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
				continue;
			}

			if(!$this->PluginsState->delete($plugin['InnerPluginsState']['id'])) {
				$this->out(__('<warning>Failed removing "updating" state on cloned plugin.</warning>'));
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
			}
		}

		$this->out(__('<info>Finished updating plugins.</info>'));
		$this->_unlock();
		return 0;
	}

	/**
	 * Fetches the list of plugin branches from SVN.
	 *
	 * @param $plugin_slug
	 *
	 * @return array
	 */
	protected function _getBranches($plugin_slug)
	{
		$svn_url = sprintf(Configure::read('App.plugin_svn_url'), $plugin_slug);

		$branches = array();

		try
		{
			$xml_list = $this->_exec(
				'svn list --xml %s', $svn_url . 'branches'
			);
			$data = Xml::build(implode('', $xml_list));
			foreach($data->list->entry as $path) {
				if($path['kind'] != 'dir')
					continue;
				$branches[] = $path->name;
			}
		}
		catch(Exception $e)
		{
			return array();
		}

		return $branches;
	}

}