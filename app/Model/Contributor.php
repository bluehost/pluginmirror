<?php
/**
 * The Contributor model represents all WordPress.org plugin contributors.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppModel', 'Model');

/**
 * Contributor Model
 *
 * @package app.Model
 *
 * @property Plugin $Plugin
 */
class Contributor extends AppModel
{

	public $displayField = 'name';

	/**
	 * hasAndBelongsToMany associations
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array('Plugin');

}