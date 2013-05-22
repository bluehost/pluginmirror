<?php
/**
 * Test group for running all application unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Case
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

/**
 * Test group for running all application unit tests.
 *
 * @package app.Test.Case
 */
class AllTest extends PHPUnit_Framework_TestSuite
{

	/**
	 * @return CakeTestSuite
	 */
	public static function suite()
	{
		$suite = new CakeTestSuite('All Application Tests');
		$suite->addTestDirectoryRecursive(APP_TEST_CASES);
		return $suite;
	}

}