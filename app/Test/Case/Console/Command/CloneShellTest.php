<?php
/**
 * Clone console shell unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Case.Console.Command
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('CloneShell', 'Console/Command');

/**
 * Clone console shell unit tests.
 *
 * @package app.Test.Case.Console.Command
 */
class CloneShellTest extends CakeTestCase
{

	public $shell;

	public function setUp()
	{
		parent::setUp();

		$out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$in = $this->getMock('ConsoleInput', array(), array(), '', false);

		$this->shell = $this->getMock('CloneShell',
			array('_checkLock', '_unlock', 'in', 'err', 'createFile', '_stop', 'clear'),
			array($out, $out, $in)
		);

		$this->shell->initialize();
		$this->shell->loadTasks();
		$this->shell->startup();
	}

	public function tearDown()
	{
		parent::tearDown();

		unset($this->shell);
	}

	public function testMain()
	{
		$this->assertIdentical($this->shell->main(), null,
		                       __('Clone shell failed.'));
	}

}