<?php
/**
 * Site HTML page footer view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Elements
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */
?>
<div class="row">
	<div class="small-12 columns">
		<hr>
		<footer>
			<p>
				<?php
				echo __('Powered by %s', $this->Html->link('PluginMirror',
				                                           'https://github.com/bluehost/pluginmirror',
				                                           array('target' => '_blank')));
				?>
			</p>
		</footer>
	</div>
</div>