<?php
/**
 * Description fixture for unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Fixture
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

/**
 * Description fixture for unit tests.
 *
 * @package app.Test.Fixture
 */
class DescriptionFixture extends CakeTestFixture
{

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'plugin_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'key' => 'unique'),
		'content' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 16384, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'plugin_id' => array('column' => 'plugin_id', 'unique' => 1)
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
			'plugin_id' => 1,
			'content' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-03-11 19:42:42',
			'modified' => '2013-03-11 19:42:42'
		),
	);

}