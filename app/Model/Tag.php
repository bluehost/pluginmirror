<?php
/**
 * The Tag model represents all WordPress.org plugin tags.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppModel', 'Model');

/**
 * Tag Model
 *
 * @package app.Model
 *
 * @property Plugin $Plugin
 */
class Tag extends AppModel
{

	public $displayField = 'name';

	/**
	 * hasAndBelongsToMany associations
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array('Plugin');

}