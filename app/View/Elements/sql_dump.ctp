<?php
/**
 * Debug SQL dump view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Elements
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

if (!class_exists('ConnectionManager') || Configure::read('debug') < 2) {
	return false;
}

$noLogs = !isset($logs);
if($noLogs) {
	$sources = ConnectionManager::sourceList();

	$logs = array();
	foreach($sources as $source) {
		$db = ConnectionManager::getDataSource($source);
		if(!method_exists($db, 'getLog'))
			continue;
		$logs[$source] = $db->getLog();
	}
}

if($noLogs || isset($_forced_from_dbo_)) {

	if(!empty($logs))
		echo "<h2>Database Query Statistics</h2>\n";

	foreach ($logs as $source => $logInfo) {
		$text = $logInfo['count'] > 1 ? 'queries' : 'query';
		printf(
			'<table id="cakeSqlLog_%s">',
			preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time(), true))
		);
		printf('<caption>(%s) %s %s took %s ms</caption>', $source, $logInfo['count'], $text, $logInfo['time']);

		echo "<thead><tr><th>#</th><th>Query</th><th>Error</th><th>Affected</th><th>Rows</th><th>Time</th></tr></thead>\n";
		echo "<tbody>\n";

		foreach ($logInfo['log'] as $k => $i) {
			$i += array('error' => '');
			if (!empty($i['params']) && is_array($i['params'])) {
				$bindParam = $bindType = null;
				if (preg_match('/.+ :.+/', $i['query'])) {
					$bindType = true;
				}
				foreach ($i['params'] as $bindKey => $bindVal) {
					if ($bindType === true) {
						$bindParam .= h($bindKey) ." => " . h($bindVal) . ", ";
					} else {
						$bindParam .= h($bindVal) . ", ";
					}
				}
				$i['query'] .= " , params[ " . rtrim($bindParam, ', ') . " ]";
			}
			echo "<tr>";
			echo "<td>" . ($k + 1) . "</td>";
			echo "<td class=\"sql-query\">" . h($i['query']) . "</td>";
			echo "<td>{$i['error']}</td>";
			echo "<td style=\"text-align: right\">{$i['affected']}</td>";
			echo "<td style=\"text-align: right\">{$i['numRows']}</td>";
			echo "<td style=\"text-align: right\">{$i['took']}</td>";
			echo "</tr>\n";
		}

		echo "</tbody></table>";
	}

} else {

	echo '<p>Encountered unexpected $logs cannot generate SQL log</p>';

}
