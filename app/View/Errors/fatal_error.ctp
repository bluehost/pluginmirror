<?php
/**
 * Debug fatal error view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Errors
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>
<h2><?php echo __d('dev', 'Fatal Error'); ?></h2>
<?php
echo $this->element('alert-error', array(
	'message' => __('<p>%s</p><p>File: %s line %d</p>',
	                $error->getMessage(),
	                $error->getFile(),
	                $error->getLine()),
	'close' => false,
));
if(Configure::read('debug') > 0) { // should be, but just in case...
	echo $this->element('exception_stack_trace');
}
