<?php
/**
 * Custom PaginatorHelper implementation for Foundation-compatible HTML.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Helper
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('PaginatorHelper', 'View/Helper');

/**
 * Custom PaginatorHelper implementation for Foundation-compatible HTML.
 *
 * @package app.View.Helper
 */
class AppPaginatorHelper extends PaginatorHelper
{

	/**
	 * @param array $options
	 *
	 * @return string
	 */
	public function pagination($options = array())
	{
		$default = array(
			'div' => 'pagination-centered',
			'units' => array('prev', 'numbers', 'next')
		);

		$options += $default;

		$units = $options['units'];
		unset($options['units']);
		$class = $options['div'];
		unset($options['div']);

		$out = array();
		foreach ($units as $unit) {
			if ($unit === 'numbers') {
				$out[] = $this->{$unit}($options);
			} else {
				$out[] = $this->{$unit}(null, $options);
			}
		}

		$list = $this->Html->tag('ul', implode("\n", $out),
		                         array('class' => 'pagination'));
		$counter = $this->Html->tag('p', $this->counter());

		return $this->Html->div($class, $list . $counter);
	}

	/**
	 * @param null  $title
	 * @param array $options
	 * @param null  $disabledTitle
	 * @param array $disabledOptions
	 * @return null|string
	 */
	public function prev($title = null, $options = array(), $disabledTitle = null, $disabledOptions = array())
	{
		$default = array(
			'title' => '«',
			'tag' => 'li',
			'model' => $this->defaultModel(),
			'class' => 'arrow',
			'disabled' => 'arrow unavailable',
		);
		$options += $default;
		if (empty($title)) {
			$title = $options['title'];
		}
		unset($options['title']);

		$disabled = $options['disabled'];
		$params = (array)$this->params($options['model']);
		if ($disabled === 'hide' && !$params['prevPage']) {
			return null;
		}
		unset($options['disabled']);

		return parent::prev($title, $options, $this->Html->tag('a', $title), array_merge($options, array(
			'escape' => false,
			'class' => $disabled,
		)));
	}

	/**
	 * @param null  $title
	 * @param array $options
	 * @param null  $disabledTitle
	 * @param array $disabledOptions
	 * @return null|string
	 */
	public function next($title = null, $options = array(), $disabledTitle = null, $disabledOptions = array())
	{
		$default = array(
			'title' => '»',
			'tag' => 'li',
			'model' => $this->defaultModel(),
			'class' => 'arrow',
			'disabled' => 'arrow unavailable',
		);
		$options += $default;
		if (empty($title)) {
			$title = $options['title'];
		}
		unset($options['title']);

		$disabled = $options['disabled'];
		$params = (array)$this->params($options['model']);
		if ($disabled === 'hide' && !$params['nextPage']) {
			return null;
		}
		unset($options['disabled']);

		return parent::next($title, $options, $this->Html->tag('a', $title), array_merge($options, array(
			'escape' => false,
			'class' => $disabled,
		)));
	}

	/**
	 * @param array $options
	 * @return mixed
	 */
	public function numbers($options = array())
	{
		$defaults = array(
			'tag' => 'li',
			'before' => null,
			'after' => null,
			'model' => $this->defaultModel(),
			'class' => null,
			'modulus' => 6,
			'separator' => false,
			'first' => 1,
			'last' => 1,
			'ellipsis' => '<li class="unavailable"><a>&hellip;</a></li>',
			'currentClass' => 'current',
			'currentTag' => 'a'
		);
		$options += $defaults;
		return parent::numbers($options);
	}

	/**
	 * @param array $options
	 * @return string
	 */
	public function counter($options = array())
	{
		if(!isset($options['format'])) {
			$options['format'] = __('Page {:page} of {:pages}, showing {:current} of {:count} total.');
		}

		return parent::counter($options);
	}

}
