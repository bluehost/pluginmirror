<?php
/**
 * Stats JSON index view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Stats
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 * @var array $stats
 */

$data = array();

foreach ($stats as &$stat) {
	$data[] = array(
		(int) $stat['Stat']['updating'],
		(int) $stat['Stat']['cloning'],
		(int) $stat['Stat']['refreshing'],
		(int) $stat['Stat']['removed'],
		(int) $stat['Stat']['cloned'],
		(int) $stat['Stat']['total'],
		$stat['Stat']['created_iso8601'],
	);
}

$data = array_reverse($data);

echo json_encode($data);