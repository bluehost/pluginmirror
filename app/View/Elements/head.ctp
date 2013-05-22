<?php
/**
 * Site HTML head view.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Elements
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 *
 * @var View $this
 */
?>
<head>
	<?php
	echo $this->Html->charset();

	if(isset($title_for_layout)) {
		echo $this->Html->tag('title', $title_for_layout .' - '. Configure::read('App.name'));
	} else {
		echo $this->Html->tag('title', Configure::read('App.name'));
	}

	echo $this->Html->meta(
		array('name' => 'viewport',
		      'content' => 'width=device-width, initial-scale=1.0'));
	echo $this->Html->meta('icon');
	echo $this->fetch('meta');

	echo $this->Html->css('normalize.min');
	echo $this->Html->css('foundation.min');
	echo $this->Html->css('font-awesome.min');
	echo $this->Html->css('application');
	echo $this->fetch('css');

	echo $this->Html->script('modernizr.min');
	?>
</head>