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

	/**
	 * Save method overridden from parent Model::save() in order to clear
	 * modified field if it's set (so all modifications result in an updated
	 * date in the modified field).
	 *
	 * @see http://book.cakephp.org/2.0/en/models/saving-your-data.html#using-created-and-modified
	 *
	 * @param array $data Data to save.
	 * @param boolean|array $validate Either a boolean, or an array.
	 *   If a boolean, indicates whether or not to validate before saving.
	 *   If an array, can have following keys:
	 *
	 *   - validate: Set to true/false to enable or disable validation.
	 *   - fieldList: An array of fields you want to allow for saving.
	 *   - callbacks: Set to false to disable callbacks. Using 'before' or 'after'
	 *      will enable only those callbacks.
	 *
	 * @param array $fieldList List of fields to allow to be saved
	 * @return mixed On success Model::$data if its not empty or true, false on failure
	 */
	public function save($data = null, $validate = true, $fieldList = array()) {
		// Clear modified field value before each save
		$this->set($data);
		if (isset($this->data[$this->alias]['modified'])) {
			unset($this->data[$this->alias]['modified']);
		}
		return parent::save($this->data, $validate, $fieldList);
	}

}