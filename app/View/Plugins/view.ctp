<?php
/**
 * Plugins view view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Plugins
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 * @var array $plugin
 */
?>
<div class="row">
	<div class="large-9 columns external-links">
		<?php
		echo $this->Html->tag('h2', $plugin['Plugin']['display_name']);
		if(in_array('removed', Hash::extract($plugin, 'PluginsState.{n}.State.name'))) {
			echo $this->element('alert-warning', array('message' =>
				__('This plugin is unlisted in the WordPress.org plugin repository, possibly ') .
				__('because of licensing issues or security problems. The code is still ') .
				__('available to clone, but be extremely careful how you use it!')
			));
		}
		if(!empty($plugin['Plugin']['description'])) {
			echo $this->Html->tag('blockquote', $this->Html->tag('p', $plugin['Plugin']['description']), array('class' => 'guide'));
		}
		if(!empty($plugin['Description']['content'])) {
			// Purposely not using HTML entities here, this should be the
			// only location that puts trust in the WordPress.org API.
			echo $plugin['Description']['content'];
		}
		?>
	</div>
	<div class="large-3 columns">
		<?php
		if(in_array('cloned', Hash::extract($plugin, 'PluginsState.{n}.State.name'))) {
			echo $this->Html->link(__('GitHub Mirror'),
				sprintf(Configure::read('App.plugin_github_url'), $plugin['Plugin']['slug']),
				array('class' => 'button large expand success radius', 'icon' => 'github large fw'));
		} else if(in_array('cloning', Hash::extract($plugin, 'PluginsState.{n}.State.name'))) {
			echo $this->Html->link(__('Cloning'), 'javascript:void(0);',
				array('class' => 'button large expand disabled radius', 'icon' => 'refresh large spin fw'));
		} else {
			echo $this->Html->link(__('Clone'),
				array('action' => 'mirror', 'slug' => $plugin['Plugin']['slug']),
				array('class' => 'button large expand radius', 'icon' => 'refresh large fw'));
		}
		?>
		<ul class="sidebar-details">
			<?php
			echo $this->Html->tag('li', __('WordPress.org Links'), array('class' => 'header'));
			if(!in_array('removed', Hash::extract($plugin, 'PluginsState.{n}.State.name'))) {
				echo $this->Html->tag('li', $this->Html->link(
					__('Plugin Homepage'), sprintf(Configure::read('App.plugin_http_url'),
					                               $plugin['Plugin']['slug']),
					array('target' => '_blank')));
			}
			echo $this->Html->tag('li', $this->Html->link(
				__('Subversion Repository'), sprintf(Configure::read('App.plugin_svn_url'),
				                                     $plugin['Plugin']['slug']),
				array('target' => '_blank')));
			echo $this->Html->tag('li', $this->Html->link(
				__('Browse in Trac'), sprintf(Configure::read('App.plugin_trac_url'),
				                              $plugin['Plugin']['slug']),
				array('target' => '_blank')));

			echo $this->Html->tag('li', __('Plugin Details'), array('class' => 'header'));
			if(!empty($plugin['Plugin']['version']) ||
			   !empty($plugin['Plugin']['requires']) ||
			   !empty($plugin['Plugin']['tested']) ||
			   !empty($plugin['Plugin']['wp_updated']) ||
			   !empty($plugin['Plugin']['added']))
			{
				if(!empty($plugin['Plugin']['version'])) {
					echo $this->Html->tag('li', sprintf('<b>%s</b> %s', __('Version:'),
					                                    h($plugin['Plugin']['version'])),
					                      array('escape' => false));
				}
				if(!empty($plugin['Plugin']['requires'])) {
					echo $this->Html->tag('li', sprintf('<b>%s</b> %s', __('Requires:'),
					                                    h($plugin['Plugin']['requires'])),
					                      array('escape' => false));
				}
				if(!empty($plugin['Plugin']['tested'])) {
					echo $this->Html->tag('li', sprintf('<b>%s</b> %s', __('Tested up to:'),
					                                    h($plugin['Plugin']['tested'])),
					                      array('escape' => false));
				}
				if(!empty($plugin['Plugin']['wp_updated'])) {
					echo $this->Html->tag('li', sprintf('<b>%s</b> %s', __('Last Updated:'),
					                                    h($plugin['Plugin']['wp_updated'])),
					                      array('escape' => false));
				}
				if(!empty($plugin['Plugin']['added'])) {
					echo $this->Html->tag('li', sprintf('<b>%s</b> %s', __('Added on:'),
					                                    h($plugin['Plugin']['added'])),
					                      array('escape' => false));
				}
			} else {
				echo $this->Html->tag('li', __('No Details Available'));
			}

			$contributors = Hash::extract($plugin, 'Contributor.{n}.name');
			if(!empty($contributors))
				echo $this->Html->tag('li', __('Contributors'), array('class' => 'header'));
			foreach($contributors as $user) {
				echo $this->Html->tag('li', $this->Html->link(
					$user, sprintf(Configure::read('App.profile_url'), $user),
					array('target' => '_blank')));
			}

			$tags = Hash::extract($plugin, 'Tag.{n}.name');
			if(!empty($tags)) {
				sort($tags);
				echo $this->Html->tag('li', __('Tags'), array('class' => 'header'));
				echo $this->Html->tag('li', implode(', ', $tags));
			}
			?>
		</ul>
	</div>
</div>