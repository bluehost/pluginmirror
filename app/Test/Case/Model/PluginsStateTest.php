<?php
/**
 * PluginsState model unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Case.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('PluginsState', 'Model');

/**
 * PluginsState model unit tests.
 *
 * @package app.Test.Case.Model
 */
class PluginsStateTest extends CakeTestCase
{

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.plugins_state',
		'app.plugin',
		'app.contributor',
		'app.contributors_plugin',
		'app.state',
		'app.tag',
		'app.plugins_tag'
	);

	/**
	 * setUp method
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();
		$this->PluginsState = ClassRegistry::init('PluginsState');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->PluginsState);
		parent::tearDown();
	}

}
