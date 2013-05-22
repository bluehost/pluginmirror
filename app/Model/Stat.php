<?php
/**
 * The Stat model represents (ideally) 10 minute interval stats on queues.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppModel', 'Model');

/**
 * The Stat model represents (ideally) 10 minute interval stats on queues.
 *
 * @package app.Model
 */
class Stat extends AppModel
{

	/**
	 * Stat constructor.
	 *
	 * @param array|bool|int|string $id Set this ID for this model on startup, can also be an array of options, see Model::__construct().
	 * @param string                $table Name of database table to use.
	 * @param string                $ds DataSource connection name.
	 */
	public function __construct($id = false, $table = null, $ds = null)
	{
		parent::__construct($id, $table, $ds);

		$this->virtualFields['created_iso8601'] = sprintf(
			'DATE_FORMAT(%1$s.created, %2$s)',
			$this->alias, "'%Y-%m-%dT%TZ'");
	}

}
