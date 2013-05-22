<?php
/**
 * Base view for pages with a sidebar.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Common
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 */
?>

<div class="row">
	<div class="large-9 columns">
		<?php echo $this->fetch('content'); ?>
	</div>
	<div class="large-3 columns">
		<?php
		// TODO: sidebar
		?>
	</div>
</div>