<?php
/**
 * Flash message layout.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Layouts
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<?php
echo $this->element('head', compact('title_for_layout'));

if (Configure::read('debug') == 0)
{
	$this->start('meta');
	echo $this->Html->meta('', "$pause;url=$url", array('http-equiv' => 'refresh'));
	$this->end();
}
?>
<body>
<div class="contain-to-grid">
	<nav class="top-bar"><?php echo $this->element('nav'); ?></nav>
</div>
<div class="row">
	<div class="small-12 columns">
		<?php echo $this->Session->flash(); ?>
		<h1><?php echo h($title_for_layout); ?></h1>
		<p><a href="<?php echo $url; ?>"><?php echo $message; ?></a></p>
	</div>
</div>
<?php echo $this->element('footer'); ?>
<?php echo $this->element('scripts'); ?>
</body>
</html>