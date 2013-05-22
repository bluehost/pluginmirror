<?php
/**
 * Default 500 error view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Errors
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>
<h2><?php echo __('Oops!'); ?></h2>
<?php
echo $this->element('alert-error', array(
	'message' => __('An internal server error has occurred, and has been logged. ') .
	             __('Sorry for the interruption, we will try to fix this quickly.'),
	'close'   => false,
));
if(Configure::read('debug') > 0)
{
	echo $this->element('exception_stack_trace');
}
