<?php
/**
 * Tag model unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Case.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('Tag', 'Model');

/**
 * Tag model unit tests.
 *
 * @package app.Test.Case.Model
 */
class TagTest extends CakeTestCase
{

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.tag',
		'app.plugin',
		'app.description',
		'app.plugins_state',
		'app.state',
		'app.contributor',
		'app.contributors_plugin',
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
		$this->Tag = ClassRegistry::init('Tag');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->Tag);
		parent::tearDown();
	}

}