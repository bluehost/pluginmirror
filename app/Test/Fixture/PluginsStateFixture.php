<?php
/**
 * PluginsState fixture for unit tests.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Test.Fixture
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

/**
 * PluginsState fixture for unit tests.
 *
 * @package app.Test.Fixture
 */
class PluginsStateFixture extends CakeTestFixture
{

	/**
	 * Fields
	 *
	 * @var array
	 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'plugin_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'index'),
		'state_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'plugin_id' => array('column' => array('plugin_id', 'state_id'), 'unique' => 1)
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
			'state_id' => 1,
			'created' => '2013-02-25 20:21:16',
			'modified' => '2013-02-25 20:21:16'
		),
	);

}