<?php
/**
 * Base model providing common functionality for all models.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('Model', 'Model');

/**
 * Base model providing common functionality for all models.
 *
 * @package app.Model
 */
class AppModel extends Model
{

	/**
	 * Defines behaviors attached to all models.
	 *
	 * @var array
	 */
	public $actsAs = array('Containable');

}