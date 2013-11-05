<?php
/**
 * Plugins index view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Plugins
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 * @var array $plugins
 */
?>
<div class="row">
	<div class="large-9 columns">
		<h2><?php echo __('Plugins'); ?></h2>

		<?php foreach ($plugins as $plugin): ?>
		<div class="panel radius no-bottom-padding">
			<div class="row">
				<div class="large-9 columns">
					<p><b><?php echo $this->Html->link(
						$plugin['Plugin']['display_name'],
						array('action' => 'view', 'slug' => $plugin['Plugin']['slug']),
						array('escape' => false));
					?></b></p>
					<p class="external-links"><?php echo $plugin['Plugin']['description']; ?></p>
					<div class="row hide-for-medium-down">
						<div class="large-4 columns">
							<?php if(!empty($plugin['Plugin']['version']))
								echo $this->Html->tag('p',
									__('Version: %s', h($plugin['Plugin']['version']))); ?>
						</div>
						<div class="large-4 columns">
							<?php if(!empty($plugin['Plugin']['requires']))
								echo $this->Html->tag('p',
									__('Requires: %s', h($plugin['Plugin']['requires']))); ?>
						</div>
						<div class="large-4 columns">
							<?php if(!empty($plugin['Plugin']['tested']))
								echo $this->Html->tag('p',
									__('Tested up to: %s', h($plugin['Plugin']['tested']))); ?>
						</div>
					</div>
				</div>
				<div class="large-3 columns">
					<?php
					if(in_array('cloned', Hash::extract($plugin, 'PluginsState.{n}.State.name'))) {
						echo $this->Html->link(__('GitHub Mirror'),
							sprintf(Configure::read('App.plugin_github_url'), $plugin['Plugin']['slug']),
							array('class' => 'button expand success radius', 'icon' => 'github fw'));
					} else if(in_array('cloning', Hash::extract($plugin, 'PluginsState.{n}.State.name'))) {
						echo $this->Html->link(__('Cloning'), 'javascript:void(0);',
							array('class' => 'button expand radius disabled', 'icon' => 'refresh spin fw'));
					} else {
						echo $this->Html->link(__('Clone'),
							array('action' => 'mirror', 'slug' => $plugin['Plugin']['slug']),
							array('class' => 'button expand radius', 'icon' => 'refresh fw'));
					}
					if(!empty($plugin['Plugin']['wp_updated'])) {
						echo $this->Html->tag('p', sprintf('%s<br>%s',
								__('Updated'), h($plugin['Plugin']['wp_updated'])),
								array('class' => 'text-center hide-for-small'));
					}
					?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>

		<?php echo $this->Paginator->pagination(); ?>
	</div>

	<div class="large-3 columns">
		<ul class="sidebar-details">
			<li class="header"><?php echo __('Sort By'); ?></li>
			<li><?php echo $this->Paginator->sort('display_name'); ?></li>
			<li><?php echo $this->Paginator->sort('created'); ?></li>
			<li><?php echo $this->Paginator->sort('modified'); ?></li>
		</ul>
	</div>
</div>