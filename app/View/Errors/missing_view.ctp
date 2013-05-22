<?php
/**
 * Debug missing view error view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Errors
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>
<h2><?php echo __d('dev', 'Missing View'); ?></h2>
<?php
echo $this->element('alert-error', array(
	'message' => __('The view for %s was not found.<br>Confirm you have created the file: %s',
	                '<em>' . Inflector::camelize($this->request->controller) .
	                'Controller::' . $this->request->action . '()</em>', substr($file, strlen(ROOT) + 1)),
	'close' => false,
));
if(Configure::read('debug') > 0) { // should be, but just in case...
	echo $this->element('exception_stack_trace');
}
