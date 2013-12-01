<?php
/**
 * CakePHP console shell for closing pull requests on mirrored repositories.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Console.Command
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppShell', 'Console/Command');

/**
 * CakePHP console shell for closing pull requests on mirrored repositories.
 *
 * @package app.Console.Command
 */
class GithubCloseShell extends AppShell {

	public $locked_commands = array('main');

	/**
	 * @var string Automated reply used when closing pull requests.
	 */
	protected $close_reply = '';

	/**
	 * Constructs this GithubCloseShell instance.
	 *
	 * @param ConsoleOutput $stdout A ConsoleOutput object for stdout.
	 * @param ConsoleOutput $stderr A ConsoleOutput object for stderr.
	 * @param ConsoleInput $stdin A ConsoleInput object for stdin.
	 */
	public function __construct($stdout = null, $stderr = null, $stdin = null)
	{
		$this->close_reply = trim('
This repository is just a mirror, pull requests are not monitored or accepted
here. Please look at the official page for instructions on how to contribute
changes back to this plugin. If none exist, we suggest posting a new topic to
address this on the support forum on WordPress.org.

Official Page: %1$s
Support Forum: %2$s
		');
		parent::__construct($stdout, $stderr, $stdin);
	}

	/**
	 * Gets the option parser instance and configures it.
	 *
	 * @return ConsoleOptionParser
	 */
	function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(__('Closes all pull requests to mirrored repos.'));
		return $parser;
	}

	/**
	 * Closes any open pull requests on mirrored repositories.
	 *
	 * Since these are mirrored repos, any pull requests submitted are not
	 * monitored or handled. Since GitHub does not support turning off pull
	 * requests, it is inevitable that users will submit them anyway.
	 *
	 * Note: GitHub supports turning off issues, but not pull requests.
	 *
	 * This utility will simply auto-close any opened pull requests while
	 * directing author to a (hopefully) more appropriate location to submit
	 * changes to.
	 *
	 * @return int Shell return code.
	 */
	function main()
	{
		$this->out(__('Fetching list of open issues...'));

		try {
			$client = new Github\Client();
			$client->getHttpClient()->setOption(
				   'user_agent', Configure::read('GitHub.user_agent'));
			$client->authenticate(GITHUB_USERNAME, GITHUB_PASSWORD,
								  Github\Client::AUTH_HTTP_PASSWORD);
		} catch(Exception $exception) {
			$this->out(__('<error>Failed to authenticate with GitHub: %s</error>',
						  $exception->getMessage()));
			$this->_unlock();
			return 1;
		}

		try {
			$response = $client->getHttpClient()->get('search/issues', array(
				'q' => sprintf('user:%s state:open', Configure::read('GitHub.org_name'))
			));
			$issues = $response->getContent();
		} catch(Exception $exception) {
			$this->out(__('<error>Failed to fetch list of open issues: %s</error>',
						  $exception->getMessage()));
			$this->_unlock();
			return 1;
		}

		if($issues['total_count'] == 0) {
			$this->out(__('<info>No open pull requests were found.</info>'));
			$this->_unlock();
			return 0;
		}

		foreach($issues['items'] as $pull_request) {
			$parts = explode('/', $pull_request['html_url']);
			$user = $parts[3];
			$plugin = $parts[4];
			$issue_number = $parts[6];
			$plugin_page = sprintf(Configure::read('App.plugin_http_url'), $plugin);
			$support_page = sprintf(Configure::read('App.plugin_support_url'), $plugin);
			$pull_request_url = sprintf('repos/%s/%s/pulls/%d', $user, $plugin, $issue_number);

			try {
				$client->getHttpClient()->post($pull_request['comments_url'],
					array('body' => sprintf($this->close_reply, $plugin_page, $support_page))
				);
				$client->getHttpClient()->patch($pull_request_url,
					array('state' => 'closed')
				);
			} catch(Exception $exception) {
				$this->out(__('<error>Failed to close pull request <%s>: %s</error>',
							  $pull_request['html_url'], $exception->getMessage()));
				continue;
			}
			$this->out(__('Closed pull request: %s', $pull_request['html_url']));
		}

		$this->out(__('<info>Finished closing open issues on GitHub.</info>'));
		$this->_unlock();
		return 0;
	}

}
