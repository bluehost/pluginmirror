<?php
/**
 * Success alert view element.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Elements
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

$data = array(
	'class' => 'success',
	'icon' => 'ok',
	'title' => 'Success!',
);

if(isset($class))   { $data['class']  .= " $class"; }
if(isset($close))   { $data['close']   = $close; }
if(isset($icon))    { $data['icon']    = $icon; }
if(isset($message)) { $data['message'] = $message; }
if(isset($title))   { $data['title']   = $title; }

echo $this->element('alert', $data);