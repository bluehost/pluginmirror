<?php
/**
 * Description model unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Case.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('Description', 'Model');

/**
 * Description model unit tests.
 *
 * @package app.Test.Case.Model
 */
class DescriptionTest extends CakeTestCase
{

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.description',
		'app.plugin',
		'app.plugins_state',
		'app.state',
		'app.contributor',
		'app.contributors_plugin',
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
		$this->Description = ClassRegistry::init('Description');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->Description);
		parent::tearDown();
	}

}