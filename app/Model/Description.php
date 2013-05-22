<?php
/**
 * The Description model represents all plugin long descriptions.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppModel', 'Model');

/**
 * Description Model
 *
 * @package app.Model
 *
 * @property Plugin $Plugin
 */
class Description extends AppModel
{

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array('Plugin');

}