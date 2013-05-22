<?php
/**
 * Tag fixture for unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Fixture
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

/**
 * Tag fixture for unit tests.
 *
 * @package app.Test.Fixture
 */
class TagFixture extends CakeTestFixture
{

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 64, 'key' => 'unique', 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'name' => array('column' => 'name', 'unique' => 1)
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
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-03-11 22:34:53'
		),
		array(
			'id' => 2,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-03-11 22:34:53'
		),
		array(
			'id' => 3,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-03-11 22:34:53'
		),
		array(
			'id' => 4,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-03-11 22:34:53'
		),
		array(
			'id' => 5,
			'name' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-03-11 22:34:53'
		),
	);

}