<?php
/**
 * Generic alert view element.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Elements
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

if(!isset($class)) { $class = false; }
if(!isset($close)) { $close = false; }
if(!isset($icon))  { $icon  = false; }
if(!isset($title)) { $title = false; }
?>
<div data-alert class="clearfix alert-box radius <?php echo ($class) ? ' ' . $class : null; ?>">
	<?php if($close) { ?><a href="#" class="close">&times;</a><?php } ?>
	<?php if($icon) { echo $this->Html->icon($icon . ' 4x pull-left'); } ?>
	<?php if($title) { ?><h4><?php echo h($title); ?></h4><?php } ?>
	<?php echo $message; ?>
</div>