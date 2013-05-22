<?php
/**
 * Custom HtmlHelper implementation for Foundation-compatible HTML.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Helper
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('HtmlHelper', 'View/Helper');

/**
 * Custom HtmlHelper implementation for Foundation-compatible HTML.
 *
 * @package app.View.Helper
 */
class AppHtmlHelper extends HtmlHelper
{

	/**
	 * Returns markup for desired icon.
	 *
	 * @param string $class Space-delimited list of non-prefixed icon classes.
	 * @param string $class Space-delimited list of non-icon classes.
	 *
	 * @return string
	 */
	public function icon($class)
	{
		$class = explode(' ', $class);
		foreach ($class as &$_class) {
			if ($_class) {
				$_class = 'icon-' . $_class;
			} else {
				unset($_class);
			}
		}
		return $this->tag('i', '', array('class' => implode(' ', $class)));
	}

	/**
	 * Custom link handler supporting "icon" option for quick links with icons.
	 *
	 * @param string $title
	 * @param null   $url
	 * @param array  $options
	 * @param bool   $confirmMessage
	 *
	 * @return string
	 */
	public function link($title, $url = null, $options = array(), $confirmMessage = false)
	{
		$default = array('icon' => null, 'escape' => true);
		$options = array_merge($default, (array)$options);
		if ($options['icon']) {
			if ($options['escape']) {
				$title = h($title);
			}
			$title = $this->icon($options['icon']) . ' ' . $title;
			$options['escape'] = false;
			unset($options['icon']);
		}
		return parent::link($title, $url, $options, $confirmMessage);
	}

}
