<?php
/**
 * Default JS layout.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Layouts
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 * @var string $scripts_for_layout
 */

echo $scripts_for_layout;

?>
<script type="text/javascript"><?php echo $this->fetch('content'); ?></script>
