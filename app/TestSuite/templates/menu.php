<?php
/**
 * Navigation for custom HTML PHPUnit reporter.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.TestSuite
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>
<div class="contain-to-grid">
	<nav class="top-bar">
		<ul class="title-area">
			<li class="name">
				<h1><a href="<?php echo $cases; ?>"><?php echo Configure::read('App.name'); ?> Test Suite</a></h1>
			</li>
			<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
		</ul>
		<section class="top-bar-section">
			<ul class="left">
				<?php foreach ($plugins as $plugin) : ?>
					<li class="divider"></li>
					<li><?php printf('<a href="%s&amp;plugin=%s">%s</a>', $cases, $plugin, $plugin); ?></li>
				<?php endforeach; ?>
				<li class="divider"></li>
				<li><a href="<?php echo $cases; ?>&amp;core=true">CakePHP</a></li>
			</ul>
		</section>
	</nav>
</div>

<div class="row">
	<div class="small-12 columns">
