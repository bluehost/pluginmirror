<?php
/**
 * Default page layout.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Layouts
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 */

if(defined('IN_TESTS')) {
	echo $this->Session->flash();
	echo $this->fetch('content');
	return;
}

?>
<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<?php echo $this->element('head', compact('title_for_layout')); ?>
<body>
<div class="contain-to-grid">
	<nav class="top-bar"><?php echo $this->element('nav'); ?></nav>
</div>
<div class="row">
	<div class="small-12 columns">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
</div>
<?php echo $this->element('footer'); ?>
<?php echo $this->element('scripts'); ?>
</body>
</html>