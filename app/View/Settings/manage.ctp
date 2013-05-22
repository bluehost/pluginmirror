<?php
/**
 * Settings manage view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Settings
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>
<div class="row">
	<div class="small-12 columns">
		<h2><?php echo h($title_for_layout); ?></h2>
		<?php echo $this->Form->create('Setting', array('class' => 'form-horizontal')); ?>
		<fieldset>
			<legend><?php echo __('General'); ?></legend>
			<div class="row">
				<div class="large-12 columns">
					<?php echo $this->Form->input('App.name', array('type' => 'text', 'label' => __('Site Name'),
					                                                'size' => 40, 'class' => 'large-4')); ?>
				</div>
			</div>
		</fieldset>
		<div class="row">
			<div class="large-12 columns">
				<?php echo $this->Form->submit(__('Save Settings'), array('div' => false, 'class' => 'radius')); ?>
			</div>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>