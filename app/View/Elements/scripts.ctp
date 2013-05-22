<?php
/**
 * Site footer scripts view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Elements
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
echo $this->Html->script('foundation.min');

$this->Html->scriptBlock("$(document).foundation();", array('inline' => false));

// Force plugin description links to open in a new tab/window.
$this->Html->scriptBlock("
	$(document).ready(function() {
		$('.external-links a').attr('target', '_blank');
	});
", array('inline' => false));

echo $this->fetch('script');