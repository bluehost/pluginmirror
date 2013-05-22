<?php
/**
 * Default 404 error view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Errors
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>
<h2><?php echo __('Not Found'); ?></h2>
<?php
echo $this->element('alert-error', array(
	'message' => __('The requested address %s was not found on this server.',
	                "<strong>'{$url}'</strong>"),
	'close'   => false,
));
if(Configure::read('debug') > 0)
{
	echo $this->element('exception_stack_trace');
}
