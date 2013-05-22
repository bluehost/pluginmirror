<?php
/**
 * Site navigation view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Elements
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>
<ul class="title-area">
	<li class="name">
		<h1><?php echo $this->Html->link(Configure::read('App.name'), array('controller' => 'pages', 'action' => 'display', 'home')); ?></h1>
	</li>
	<li class="toggle-topbar menu-icon"><a href="#"><span><?php echo __('Menu'); ?></span></a></li>
</ul>
<section class="top-bar-section">
	<ul class="left">
		<li class="divider"></li>
		<li><?php echo $this->Html->link(__('Plugins'), array('controller' => 'plugins', 'action' => 'index')); ?></li>
		<li class="divider"></li>
		<li><?php echo $this->Html->link(__('Status'), array('controller' => 'stats', 'action' => 'index')); ?></li>
		<li class="divider"></li>
		<li><?php echo $this->Html->link(__('About'), array('controller' => 'pages', 'action' => 'display', 'about')); ?></li>
		<li class="divider"></li>
		<li><?php echo $this->Html->link(__('Contact'), array('controller' => 'pages', 'action' => 'display', 'contact')); ?></li>
	</ul>
</section>