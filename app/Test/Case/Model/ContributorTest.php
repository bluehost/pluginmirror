<?php
/**
 * Contributor model unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Case.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('Contributor', 'Model');

/**
 * Contributor model unit tests.
 *
 * @package app.Test.Case.Model
 */
class ContributorTest extends CakeTestCase
{

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.contributor',
		'app.plugin',
		'app.description',
		'app.plugins_state',
		'app.state',
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
		$this->Contributor = ClassRegistry::init('Contributor');
	}

	/**
	 * tearDown method
	 *
	 * @return void
	 */
	public function tearDown()
	{
		unset($this->Contributor);
		parent::tearDown();
	}

}