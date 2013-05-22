<?php
/**
 * StatsController Test Case
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Case.Controller
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('StatsController', 'Controller');

/**
 * StatsController Test Case
 *
 * @package app.Test.Case.Controller
 */
class StatsControllerTest extends ControllerTestCase
{

	/**
	 * Fixtures
	 *
	 * @var array
	 */
	public $fixtures = array(
		'app.stat',
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

}
