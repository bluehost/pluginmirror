<?php
/**
 * State model unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Case.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('State', 'Model');

/**
 * State model unit tests.
 *
 * @package app.Test.Case.Model
 */
class StateTest extends CakeTestCase
{

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.state',
		'app.plugin'
	);

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
		$this->State = ClassRegistry::init('State');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->State);
		parent::tearDown();
	}

}
