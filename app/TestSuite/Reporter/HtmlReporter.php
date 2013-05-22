<?php
/**
 * Custom HTML PHPUnit reporter for customized output.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.TestSuite.Reporter
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('CakeHtmlReporter', 'TestSuite/Reporter');

/**
 * Custom HTML PHPUnit reporter for customized output.
 *
 * @package app.TestSuite.Reporter
 */
class HtmlReporter extends CakeHtmlReporter
{

	/**
	 * Renders the HTML header.
	 *
	 * @return void
	 */
	public function paintDocumentStart()
	{
		ob_start();
		/** @noinspection PhpUnusedLocalVariableInspection */
		$baseDir = $this->params['baseDir'];
		include APP . 'TestSuite' . DS . 'templates' . DS . 'header.php';
	}

	/**
	 * Renders the menu on the top of the test suite interface.
	 *
	 * @return void
	 */
	public function paintTestMenu()
	{
		/** @noinspection PhpUnusedLocalVariableInspection */
		$cases = $this->baseUrl() . '?show=cases';
		$plugins = App::objects('plugin', null, false);
		sort($plugins);
		include APP . 'TestSuite' . DS . 'templates' . DS . 'menu.php';
	}

	/**
	 * Renders the summary of test passes and failures.
	 *
	 * @param PHPUnit_Framework_TestResult $result Result object
	 *
	 * @return void
	 */
	public function paintFooter($result)
	{
		ob_end_flush();
		echo '</ul>';

		if($result->failureCount() + $result->errorCount() > 0)
			echo '<div class="alert-box alert radius">';
		else
			echo '<div class="alert-box success radius">';

		echo ($result->count() - $result->skippedCount()) . ' of ';
		echo $result->count() . ' test methods complete: ';
		echo count($result->passed()) . ' passes, ';
		echo $result->failureCount() . ' fails, ';
		echo $this->numAssertions . ' assertions and ';
		echo $result->errorCount() . ' exceptions.';

		echo '</div>';

		echo '<p><strong>Time:</strong> ' . __('%0.5f seconds', $result->time()) . '</p>';
		echo '<p><strong>Peak Memory:</strong> ' . number_format(memory_get_peak_usage()) . ' bytes</p>';
		$this->_paintLinks();

		if (isset($this->params['codeCoverage']) && $this->params['codeCoverage']) {
			$coverage = $result->getCodeCoverage();
			if (method_exists($coverage, 'getSummary')) {
				$report = $coverage->getSummary();
				$this->paintCoverage($report);
			}
			if (method_exists($coverage, 'getData')) {
				$report = $coverage->getData();
				$this->paintCoverage($report);
			}
		}
		$this->paintDocumentEnd();
	}

	/**
	 * Renders the HTML footer.
	 *
	 * @return void
	 */
	public function paintDocumentEnd()
	{
		/** @noinspection PhpUnusedLocalVariableInspection */
		$baseDir = $this->params['baseDir'];
		include APP . 'TestSuite' . DS . 'templates' . DS . 'footer.php';
		if (ob_get_length()) {
			ob_end_flush();
		}
	}

}