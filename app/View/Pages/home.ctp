<?php
/**
 * Static Page: Home
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Pages
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 */

$this->extend('/Common/sidebar');

?>

<div class="panel radius">
	<p class="lead">
		Say hello to fully automated GitHub mirrors of every plugin in the
		WordPress.org plugin repository. These aren't your typical plugin Git
		repositories. These mirrors can be used for fast, efficient, and
		automated plugin updates using Composer, and don't require "sync
		scripts" or separate Subversion checkouts for plugin development. They
		also offer a way for plugin developers to make the move to Git even
		while others continue working on the same plugin using Subversion
		uninterrupted.
	</p>
	<div class="row">
		<div class="small-10 small-centered columns">
			<div class="row">
				<div class="large-6 columns">
					<?php
					echo $this->Html->link(__('Browse Plugins'),
						array('controller' => 'plugins', 'action' => 'index'),
						array('class' => 'button expand radius', 'icon' => 'list large'));
					?>
				</div>
				<div class="large-6 columns">
					<?php
					echo $this->Html->link(__('How It Works'),
						array('controller' => 'plugins', 'action' => 'index'),
						array('class' => 'button expand radius', 'icon' => 'info-sign large'));
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<h2>Guides</h2>

<blockquote class="guide">
	<p>
		<?php echo $this->Html->link(__('Automatic Plugin Updates'), array(
			'controller' => 'pages', 'action' => 'display', 'automatic-plugin-updates')); ?>
		- Managing plugins using Composer.
		<cite>Published in April 2013 by Bryan Petty</cite>
	</p>
</blockquote>

<blockquote class="guide">
	<p>
		<?php echo $this->Html->link(__('Plugin Developer Guide'), array(
			'controller' => 'pages', 'action' => 'display', 'plugin-developer-guide')); ?>
		- The black art of using Git with a SVN repository.
		<cite>Published in April 2013 by Bryan Petty</cite>
	</p>
</blockquote>

<blockquote class="guide">
	<p>
		<?php echo $this->Html->link(__('Add New Guide'),
			array('controller' => 'pages', 'action' => 'display', 'add-new-guide'),
			array('icon' => 'plus-sign')); ?>
		- How are you using the plugin mirror?
		<cite>Published in April 2013 by Bryan Petty</cite>
	</p>
</blockquote>
