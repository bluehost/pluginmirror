<?php
/**
 * Stat fixture for unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Fixture
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

/**
 * Stat fixture for unit tests.
 *
 * @package app.Test.Fixture
 */
class StatFixture extends CakeTestFixture
{

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'total' => array('type' => 'integer', 'null' => true, 'default' => null),
		'cloned' => array('type' => 'integer', 'null' => true, 'default' => null),
		'cloning' => array('type' => 'integer', 'null' => true, 'default' => null),
		'refreshing' => array('type' => 'integer', 'null' => true, 'default' => null),
		'removed' => array('type' => 'integer', 'null' => true, 'default' => null),
		'updating' => array('type' => 'integer', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'created' => array('column' => 'created', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	/**
	 * Records
	 *
	 * @var array
	 */
	public $records = array(
		array(
			'id' => 1,
			'total' => 1,
			'cloned' => 1,
			'cloning' => 1,
			'refreshing' => 1,
			'removed' => 1,
			'updating' => 1,
			'created' => '2013-04-05 01:43:52'
		),
	);

}
