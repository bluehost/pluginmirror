<?php
/**
 * CakePHP console shell for providing the initial git-svn clone of a plugin.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Console.Command
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppShell', 'Console/Command');
App::uses('Folder', 'Utility');

/**
 * CakePHP console shell for providing the initial git-svn clone of a plugin.
 *
 * @package app.Console.Command
 *
 * @property Plugin $Plugin
 * @property PluginsState $PluginsState
 */
class CloneShell extends AppShell {

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
		$parser->description(__('Performs the initial git-svn clone of plugins.'));
		$parser->addArgument('max', array('help' => __('Maximum number of plugins to clone.')));
		return $parser;
	}

	/**
	 * Performs the initial git-svn clone of plugins.
	 *
	 * This shell grabs plugins that require their initial clone in the order
	 * they were requested, and clones them from the WordPress SVN repository.
	 *
	 * If unspecified, a maximum of 5 plugins will be cloned with one run.
	 *
	 * @return int Shell return code.
	 */
	function main()
	{
		$max = 5;
		if(isset($this->args[0]) && is_numeric($this->args[0]) && $this->args[0] > 0) {
			$max = (int) $this->args[0];
		}

		$plugins = $this->Plugin->findByState('cloning', array(
			'contain' => array(
				'PluginsState' => array('State'),
			),
			'order' => array('InnerPluginsState.modified'),
			'limit' => $max,
		));

		if(count($plugins) == 0) {
			$this->out(__('<info>No plugins need to be cloned.</info>'));
			$this->_unlock();
			return 0;
		}

		$this->out(__('Cloning %d plugins...', count($plugins)));

		$dir = new Folder(TMP . 'git', true, 0755);
		$error = implode(', ', $dir->errors());
		if(!empty($error)) {
			$this->_unlock();
			$this->error(__('Filesystem Error'),
			             __('Failed to create git clone directory: %s', $error));
		}
		$dir = new Folder(TMP . 'logs' . DS . 'git', true, 0755);
		$error = implode(', ', $dir->errors());
		if(!empty($error)) {
			$this->_unlock();
			$this->error(__('Filesystem Error'),
			             __('Failed to create git logs directory: %s', $error));
		}

		foreach($plugins as $plugin)
		{
			$this->out(__('Cloning: "%s" (%d)',
			              $plugin['Plugin']['slug'],
			              $plugin['Plugin']['id']));

			$svn_url = sprintf(Configure::read('App.plugin_svn_url'), $plugin['Plugin']['slug']);
			$git_path = sprintf(Configure::read('App.plugin_repo_path'), $plugin['Plugin']['slug']);
			$log_path = TMP . 'logs' . DS . 'git' . DS . $plugin['Plugin']['slug'] . '.log';

			// Clear out any existing git-svn clone attempt that failed before.
			$git_dir = new Folder($git_path);
			$git_dir->delete();

			try
			{
				$this->_exec(
					'git svn clone -qq --prefix=svn/ -s %s %s >> %s 2>&1',
					$svn_url, $git_path, $log_path
				);
			}
			catch(RuntimeException $e)
			{
				$this->out(__('<warning>Failed to clone "%s", please check the git log file.</warning>',
				              $plugin['Plugin']['slug']));
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
				continue;
			}

			if(!$this->_createGithubRepo($plugin['Plugin']['slug'])) {
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
				continue;
			}

			if(!$this->PluginsState->findOrCreate($plugin['Plugin']['id'], 'cloned') ||
			   !$this->PluginsState->findOrCreate($plugin['Plugin']['id'], 'updating')) {
				$this->out(__('<warning>Failed marking plugin as cloned.</warning>'));

				// Even though this plugin cloned successfully, if we can't
				// mark it as cloned, we don't want it to slip into limbo, so
				// we'll let it attempt to do the full clone all over again.
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
				continue;
			}

			if(!$this->PluginsState->delete($plugin['InnerPluginsState']['id'])) {
				$this->out(__('<warning>Failed removing "cloning" state on cloned plugin.</warning>'));
			}
		}

		$this->out(__('<info>Finished cloning plugins.</info>'));
		$this->_unlock();
		return 0;
	}

	/**
	 * Creates a GitHub repository with the given name.
	 *
	 * @param string $repo Name of the repository (slug)
	 *
	 * @return bool True if GitHub repository was created, false otherwise.
	 */
	protected function _createGithubRepo($repo)
	{
		try {
			$client = new Github\Client();
			$client->getHttpClient()->setOption(
				'user_agent', Configure::read('GitHub.user_agent'));
			$client->authenticate(GITHUB_USERNAME, GITHUB_PASSWORD,
			                      Github\Client::AUTH_HTTP_PASSWORD);

			$client->getHttpClient()->post(
				'orgs/' . Configure::read('GitHub.org_name') . '/repos',
				array(
					'name' => $repo,
					'description' => Configure::read('GitHub.repo_description'),
					'homepage' => sprintf(Configure::read('App.plugin_app_url'), $repo),
					'has_issues' => false,
					'has_wiki' => false,
					'has_downloads' => false,
				)
			);
		} catch(Exception $exception) {
			$this->out(__('<warning>Failed to create GitHub repository for "%s": %s</warning>',
			              $repo, $exception->getMessage()));
			// TODO: Return false properly on failure (temp fix).
			return true;
		}

		return true;
	}

}