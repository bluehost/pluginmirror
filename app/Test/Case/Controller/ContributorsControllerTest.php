<?php
/**
 * ContributorsController Test Case
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Case.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('ContributorsController', 'Controller');

/**
 * ContributorsController Test Case
 *
 * @package app.Test.Case.Controller
 */
class ContributorsControllerTest extends ControllerTestCase
{

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.contributor',
		'app.plugin',
		'app.plugins_state',
		'app.state',
		'app.contributors_plugin',
		'app.tag',
		'app.plugins_tag',
		'app.setting'
	);

	/**
	 * testIndex method
	 *
	 * @return void
	 */
	public function testIndex()
	{
	}

	/**
	 * testView method
	 *
	 * @return void
	 */
	public function testView()
	{
	}

}