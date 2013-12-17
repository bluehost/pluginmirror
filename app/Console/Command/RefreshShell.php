<?php
/**
 * CakePHP console shell for refreshing plugin info from WordPress.org.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Console.Command
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppShell', 'Console/Command');

/**
 * CakePHP console shell for refreshing plugin info from WordPress.org.
 *
 * @package app.Console.Command
 *
 * @property Contributor $Contributor
 * @property Plugin $Plugin
 * @property PluginsState $PluginsState
 * @property Tag $Tag
 */
class RefreshShell extends AppShell {

	public $uses = array(
		'Contributor',
		'Plugin',
		'PluginsState',
		'Tag',
	);

	/**
	 * Gets the option parser instance and configures it.
	 *
	 * @return ConsoleOptionParser
	 */
	function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(__('Refreshes plugin information with current details from WordPress.org.'));
		$parser->addArgument('max', array('help' => __('Maximum number of plugins to refresh.')));
		$parser->addSubcommand('queue', array(
			'help' => __('Queues plugins for a refresh if they have stale data.'),
			'parser' => array(
				'description' => array(
					__('All plugins with data older than the given number of days is added to the refresh queue.')
				),
				'arguments' => array(
					'days' => array(
						'help' => __('Number of days old plugin data can be before being queued for a refresh.'),
						'required' => false,
					),
				),
			),
		));
		return $parser;
	}

	/**
	 * Refreshes plugin information with current details from WordPress.org.
	 *
	 * This shell grabs the oldest plugins that require a refresh, and fetches
	 * their plugin details from the WordPress.org Plugin API.
	 *
	 * If unspecified, a maximum of 10 plugins will be refreshed with one run.
	 *
	 * Plugins added or updated within the last hour are skipped in order to
	 * give the WordPress.org plugin repo time to update their info.
	 *
	 * @todo Break this function up into smaller, testable pieces.
	 *
	 * @return int Shell return code.
	 */
	function main()
	{
		$max = 10;
		if(isset($this->args[0]) && is_numeric($this->args[0]) && $this->args[0] > 0) {
			$max = (int) $this->args[0];
		}

		$plugins = $this->Plugin->findByState('refreshing', array(
			'contain' => array(
				'Contributor',
				'Description',
				'PluginsState' => array('State'),
				'Tag',
			),
			'conditions' => array(
				'InnerPluginsState.modified <' => date('Y-m-d H:i:s', strtotime('1 hour ago')),
			),
			'order' => array('InnerPluginsState.modified'),
			'limit' => $max,
		));

		if(count($plugins) == 0) {
			$this->out(__('<info>All plugins are up-to-date.</info>'));
			$this->_unlock();
			return 0;
		}

		$this->out(__('Refreshing %d plugins...', count($plugins)));

		// Setup the HTTP system.
		App::uses('HttpSocket', 'Network/Http');
		$socket = new HttpSocket(array(
			'header' => array(
				'User-Agent' => Configure::read('App.name')
			)
		));

		// All successfully updated plugins will go in here to be saved.
		$updated_plugins = array();

		foreach($plugins as $plugin)
		{
			$response = $socket->get(sprintf(Configure::read('App.plugin_api_url'), $plugin['Plugin']['slug']),
			                         array(), array('redirect' => 1));
			if(!$response->isOk()) {
				$this->out(__('<warning>Failed to fetch "%s", HTTP response code: %d</warning>',
				              $plugin['Plugin']['slug'], $response->code));
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
				continue;
			}

			$data = json_decode($response->body(), true);

			$json_errors = array(
				JSON_ERROR_CTRL_CHAR => __('Unexpected control character found.'),
				JSON_ERROR_DEPTH => __('Maximum stack depth exceeded.'),
				JSON_ERROR_STATE_MISMATCH => __('Underflow or the modes mismatch.'),
				JSON_ERROR_SYNTAX => __('Syntax error, malformed JSON.'),
				JSON_ERROR_UTF8 => __('Malformed UTF-8 characters.'),
			);
			$error_code = json_last_error();
			if($error_code != JSON_ERROR_NONE) {
				$message = __('Unknown error.');
				if(isset($json_errors[$error_code]))
					$message = $json_errors[$error_code];
				$this->out(__('<warning>Failed to fetch "%s", JSON error: %s</warning>',
				              $plugin['Plugin']['slug'], $message));
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
				continue;
			}

			if(is_null($data)) {
				$this->out(__('Removed: "%s" (%d)', $plugin['Plugin']['slug'], $plugin['Plugin']['id']));

				if(!$this->PluginsState->findOrCreate($plugin['Plugin']['id'], 'removed')) {
					$this->out(__('<warning>Failed marking plugin as removed.</warning>'));
				}

				$updated_plugins[] = $plugin;
				continue;
			}

			// Clear the removed state of any plugins that are no longer missing.
			foreach($plugin['PluginsState'] as $state) {
				if($state['State']['name'] == 'removed') {
					if(!$this->PluginsState->delete($state['id'])) {
						$this->out(__('<warning>Failed clearing removed state: %s (%d).</warning>',
						              $plugin['Plugin']['slug'], $plugin['Plugin']['id']));
					}
				}
			}

			$plugin['Plugin']['name'] = $data['name'];
			$plugin['Plugin']['version'] = $data['version'];
			$plugin['Plugin']['requires'] = $data['requires'];
			$plugin['Plugin']['tested'] = $data['tested'];
			$plugin['Plugin']['wp_updated'] = $data['last_updated'];
			$plugin['Plugin']['added'] = $data['added'];
			$plugin['Plugin']['description'] = $data['short_description'];

			if(!empty($data['sections']['description'])) {
				$plugin['Description']['content'] = $data['sections']['description'];
				if(!empty($plugin['Description']['modified'])) {
					unset($plugin['Description']['modified']);
				}
			}

			$plugin['Contributor'] = array();
			try {
				$plugin['Contributor'] = $this->_saveContributors($plugin, $data);
			} catch(PDOException $exception) {
				$this->out(__('<warning>Failed to save contributors for "%s" (%d): %s</warning>',
				              $plugin['Plugin']['slug'], $plugin['Plugin']['id'], $exception->getMessage()));
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
				continue;
			}

			$plugin['Tag'] = array();
			try {
				$plugin['Tag'] = $this->_saveTags($plugin, $data);
			} catch(PDOException $exception) {
				$this->out(__('<warning>Failed to save tags for "%s" (%d): %s</warning>',
				              $plugin['Plugin']['slug'], $plugin['Plugin']['id'], $exception->getMessage()));
				$this->PluginsState->touch($plugin['InnerPluginsState']['id']);
				continue;
			}

			$updated_plugins[] = $plugin;
			$this->out(__('Updated: "%s" (%d)', $plugin['Plugin']['slug'], $plugin['Plugin']['id']));
		}

		if(empty($updated_plugins)) {
			$this->_unlock();
			return 0;
		}

		// We handle plugin states manually, don't save these.
		$updated_plugins = Hash::remove($updated_plugins, '{n}.PluginsState');

		if(!$this->Plugin->saveAll($updated_plugins, array('deep' => true))) {
			$this->_unlock();
			$this->error(__('Database Error'), __('Failed saving plugin details to the database.'));
		}

		$this->out(__('<info>All plugin updates saved.</info>'));

		// Remove the refreshing state on all plugins last.
		if(!$this->PluginsState->deleteAll(array('PluginsState.id' =>
			Hash::extract($updated_plugins, '{n}.InnerPluginsState.id')))) {
			$this->out(__('<warning>Failed marking plugins as refreshed.</warning>'));
		}

		$this->_unlock();
		return 0;
	}

	/**
	 * Creates new Contributor records as necessary, and records appropriate
	 * record IDs for those in the Plugin.
	 *
	 * @param $plugin
	 * @param $data
	 *
	 * @return array
	 *
	 * @throws PDOException Failed to create new Contributor record.
	 */
	protected function _saveContributors($plugin, $data)
	{
		$contributors = array();
		foreach($data['contributors'] as $name => $url) {
			// Only save real wp.org usernames.
			if(!preg_match('/profiles\.wordpress\.org/', $url))
				continue;
			$record = $this->Contributor->findByName($name);
			if(!$record) {
				$this->Contributor->create(array('name' => $name));
				$record = $this->Contributor->save();
			}
			$record['Contributor']['ContributorsPlugin'] = array(
				'contributor_id' => $record['Contributor']['id'],
				'plugin_id' => $plugin['Plugin']['id'],
			);
			$contributors[] = $record['Contributor'];
		}
		return $contributors;
	}

	/**
	 * Creates new Tag records as necessary, and records appropriate
	 * record IDs for those in the Plugin.
	 *
	 * @param $plugin
	 * @param $data
	 *
	 * @return array
	 *
	 * @throws PDOException Failed to create new Tag record.
	 */
	protected function _saveTags($plugin, $data)
	{
		$tags = array();

		$inflected_tags = array();
		foreach($data['tags'] as $name) {
			$inflected_tags[] = strtolower(Inflector::slug($name, ' '));
		}
		// Inflected tags could result in duplicate tags.
		$inflected_tags = array_unique($inflected_tags);

		foreach($inflected_tags as $name) {
			$record = $this->Tag->findByName($name);
			if(!$record) {
				$this->Tag->create(array('name' => $name));
				$record = $this->Tag->save();
			}
			// Skip this tag if it has already been added.
			if(Hash::check($plugin, sprintf('Tag.{n}[id=%d]', $record['Tag']['id']))) {
				continue;
			}
			$record['Tag']['PluginsTag'] = array(
				'plugin_id' => $plugin['Plugin']['id'],
				'tag_id' => $record['Tag']['id'],
			);
			$tags[] = $record['Tag'];
		}

		return $tags;
	}

	/**
	 * Queues plugins for a refresh if they have stale data.
	 *
	 * All plugins with data older than the given number of days are added to
	 * the refresh queue.
	 *
	 * @return int Shell return code.
	 */
	function queue()
	{
		$max = 14;
		if(isset($this->args[0]) && is_numeric($this->args[0]) && $this->args[0] > 0) {
			$max = (int) $this->args[0];
		}

		$plugins = $this->Plugin->find('list', array(
			'fields' => array('id', 'modified'),
			'conditions' => array(
				'modified <' => date('Y-m-d H:i:s', strtotime("$max days ago")),
			),
			'order' => array('modified'),
		));

		if(count($plugins) == 0) {
			$this->out(__('<info>No plugins need to be refreshed.</info>'));
			return 0;
		}

		$this->out(__('Marking %d plugins for refresh...', count($plugins)));

		foreach($plugins as $plugin_id => $modified) {
			$this->PluginsState->findOrCreate($plugin_id, 'refreshing');
		}

		$this->out(__('<info>Finished queuing stale plugins for refresh.</info>'));
		return 0;
	}

}