<?php
/**
 * The Plugin model represents all WordPress.org plugins.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppModel', 'Model');

/**
 * Plugin Model
 *
 * @package app.Model
 *
 * @property Contributor $Contributor
 * @property Description $Description
 * @property PluginsState $PluginsState
 * @property Tag $Tag
 */
class Plugin extends AppModel
{

	public $displayField = 'display_name';

	/**
	 * hasAndBelongsToMany associations
	 *
	 * @var array
	 */
	public $hasAndBelongsToMany = array('Contributor', 'Tag');

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'PluginsState' => array('dependent' => true),
	);

	/**
	 * hasOne associations
	 *
	 * @var array
	 */
	public $hasOne = array('Description');

	/**
	 * Property data validation settings.
	 *
	 * @var array
	 */
	public $validate = array(
		'slug' => array(
			'maxlength' => array(
				'rule' => array('maxlength', 255),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
//			'custom' => array(
//				'rule' => array('custom'),
//				//'message' => 'Your custom message here',
//				//'allowEmpty' => false,
//				//'required' => false,
//				//'last' => false, // Stop validation after this rule
//				//'on' => 'create', // Limit validation to 'create' or 'update' operations
//			),
		),
	);

	/**
	 * Plugin constructor.
	 *
	 * @param array|bool|int|string $id Set this ID for this model on startup, can also be an array of options, see Model::__construct().
	 * @param string                $table Name of database table to use.
	 * @param string                $ds DataSource connection name.
	 */
	public function __construct($id = false, $table = null, $ds = null)
	{
		parent::__construct($id, $table, $ds);

		$this->virtualFields['display_name'] = sprintf('IFNULL(%1$s.name, %1$s.slug)', $this->alias);
	}

	/**
	 * Find all plugins with all of the states provided.
	 *
	 * @param string|array $states State name or array of states to search for.
	 * @param array        $query Additional find conditions.
	 *
	 * @return array Array of plugin records, or null on failure.
	 */
	public function findByState($states, $query = array())
	{
		if(!is_array($states)) {
			$states = array($states);
		}

		$this->bindModel(array('hasOne' => array(
			'InnerPluginsState' => array(
				'className' => 'PluginsState',
				'foreignKey' => false,
				'type' => 'INNER',
				'conditions' => array(
					'InnerPluginsState.plugin_id = Plugin.id'
				),
			),
			'InnerState' => array(
				'className' => 'State',
				'foreignKey' => false,
				'type' => 'INNER',
				'conditions' => array(
					'InnerState.id = InnerPluginsState.state_id',
					'InnerState.name' => $states,
				),
			),
		)));

		$query = Hash::merge($query, array(
			'contain' => array(
				'InnerPluginsState',
				'InnerState',
			),
			'group' => array(
				'Plugin.id',
				'HAVING COUNT(Plugin.id) =' => count($states),
			),
		));

		return $this->find('all', $query);
	}

}