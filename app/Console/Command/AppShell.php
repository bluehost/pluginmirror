<?php
/**
 * Base console shell.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Console.Command
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('Shell', 'Console');

/**
 * Base console shell.
 *
 * @package app.Console.Command
 *
 * @property Setting $Setting
 */
class AppShell extends Shell
{

	/**
	 * @var array Shell commands to enable locking on.
	 */
	public $locked_commands = array();

	/**
	 * Gets the option parser instance and configures it.
	 *
	 * @return ConsoleOptionParser
	 */
	function getOptionParser()
	{
		$parser = parent::getOptionParser();
		$parser->addOptions(array(
			'no-colors' => array(
				'help' => __('Do not use colors in output.'),
				'boolean' => true
			),
		));

		if(!empty($this->locked_commands)) {
			$parser->addOption('skip-lock', array(
				'short' => 's',
				'help' => __('Forces the command to run even if locked.'),
				'boolean' => true
			));
		}

		return $parser;
	}

	/**
	 * Loads database settings, disables console colors if requested, and
	 * prevents re-entry if the current command is locked.
	 *
	 * @return void
	 */
	function startup()
	{
		if(empty($this->Setting)) {
			App::uses('ClassRegistry', 'Utility');
			$this->Setting = ClassRegistry::init('Setting');
		}
		$this->Setting->load();

		$this->_checkColors();
		$this->_checkLock();

		parent::startup();
	}

	/**
	 * Disables console colors if requested.
	 *
	 * @return void
	 */
	protected function _checkColors()
	{
		if(isset($this->params['no-colors']) && $this->params['no-colors']) {
			$this->stdout->outputAs(ConsoleOutput::PLAIN);
			$this->stderr->outputAs(ConsoleOutput::PLAIN);
		}
	}

	/**
	 * @return string Fully qualified configuration setting for the current command lock state.
	 */
	protected function _getLockName()
	{
		if(empty($this->command)) {
			return implode('.', array('Shell', $this->name, 'main', 'lock'));
		}
		return implode('.', array('Shell', $this->name, $this->command, 'lock'));
	}

	/**
	 * @return bool True if the current command uses locking.
	 */
	protected function _lockRequested()
	{
		if(empty($this->locked_commands)) {
			return false;
		}
		if(empty($this->command)) {
			return in_array('main', $this->locked_commands);
		}
		return in_array($this->command, $this->locked_commands);
	}

	/**
	 * Prevents re-entry if the current command is locked.
	 *
	 * @return void
	 */
	protected function _checkLock()
	{
		if($this->_lockRequested())
		{
			if(!$this->params['skip-lock'] && Configure::read($this->_getLockName())) {
				$this->error(__('Shell Locked'), __('There is another instance of this shell running already. Use --skip-lock to force this shell to run anyway.'));
			}
			$this->Setting->write($this->_getLockName(), true);
		}
	}

	/**
	 * Erases the current command lock if it is set.
	 *
	 * @return mixed
	 */
	protected function _unlock()
	{
		return $this->Setting->write($this->_getLockName(), false);
	}

	/**
	 * Wrapper around PHP exec() that automatically escapes shell args.
	 *
	 * @param string $command
	 * @internal param mixed $arg [optional]
	 * @internal param $mixed $... [optional]
	 *
	 * @throws RuntimeException
	 *
	 * @return array Lines of output from running the given command.
	 */
	protected function _exec($command)
	{
		$return_code = 0;
		$output = array();

		if(func_num_args() > 1) {
			$args = func_get_args();
			array_shift($args);
			$args = array_map('escapeshellarg', $args);
			$command = vsprintf($command, $args);
		}

		exec($command, $output, $return_code);

		if($return_code !== 0) {
			throw new RuntimeException(
				__('Command returned with an error.'), $return_code);
		}

		return $output;
	}

}
