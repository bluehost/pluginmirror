<?php
/**
 * The Setting model represents database-backed settings that override core
 * settings (but can be overridden by settings.php settings).
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.Model
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppModel', 'Model');

/**
 * Setting Model
 *
 * @package app.Model
 */
class Setting extends AppModel
{

	/**
	 * Default field returned on list operations, and used in bake templates.
	 *
	 * @var string
	 */
	public $displayField = 'key';

	/**
	 * Setting constructor.
	 *
	 * @param array|bool|int|string $id Set this ID for this model on startup, can also be an array of options, see Model::__construct().
	 * @param string                $table Name of database table to use.
	 * @param string                $ds DataSource connection name.
	 */
	function __construct($id = false, $table = null, $ds = null)
	{
		parent::__construct($id, $table, $ds);

		// Validation rules for all settings application-wide.
		$this->validate = array(
			'App.name' => array(
				'maxLength' => array(
					'rule'       => array('maxLength', 40),
					'allowEmpty' => false,
					'message' => __('Name is required, and must be less than 40 characters long.')
				),
			),
		);
	}

	/**
	 * Update all the settings. Validation rules are also configured here.
	 *
	 * @param array $data Associative array of all settings to save.
	 *
	 * @return boolean
	 */
	function update($data = array())
	{
		$flat_data = Hash::flatten($data);
		$this->set($flat_data);

		if ($this->validates()) {
			$list = $this->find('list', array('fields' => array('key', 'id')));
			foreach ($flat_data as $key => $value) {
				if(array_key_exists($key, $list)) {
					// This setting already exists in the DB, just update it.
					$this->id = $list[$key];
					$this->saveField('value', $value);
				} else {
					// This is a new setting, add a new entry.
					$this->create();
					$this->save(array('key' => $key, 'value' => $value));
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * Reads all settings from the database, and writes them to the configuration.
	 *
	 * @return void
	 */
	function load()
	{
		Configure::write($this->find('list', array('fields' => array('key', 'value'))));

		/* Settings found here always override anything set in the database. */
		Configure::load('settings');
	}

	/**
	 * Save an individual setting to the database.
	 *
	 * @param string $key The dot-notation form of the setting key.
	 * @param mixed  $value Any serializable value.
	 *
	 * @return bool|mixed On success data if its not empty or true, false on failure
	 */
	function write($key, $value)
	{
		$setting = $this->findByKey($key);
		if(!empty($setting)) {
			$this->id = $setting['Setting']['id'];
			$setting = $this->saveField('value', $value, true);
			$this->load();
			return $setting;
		}
		$this->create(array('key' => $key, 'value' => $value));
		$setting = $this->save();
		$this->load();
		return $setting;
	}

	/**
	 * Unserialize all settings fetched from the database before being used.
	 *
	 * @param mixed $results The results of the find operation.
	 * @param bool  $primary Whether this model is being queried directly (vs. being queried as an association)
	 *
	 * @return array Unserialized setting data.
	 */
	function afterFind($results, $primary)
	{
		if($primary) {
			foreach($results as &$result) {
				if(!empty($result['Setting']['value'])) {
					$result['Setting']['value'] = unserialize($result['Setting']['value']);
				}
			}
		}
		return $results;
	}

	/**
	 * Serialize all settings saved to the database.
	 *
	 * @param array $options
	 *
	 * @return bool Always returns true to continue saving regardless of whether
	 *              it found data that needed to be serialized or not.
	 */
	function beforeSave($options)
	{
		if(!empty($this->data['Setting']['value'])) {
			$this->data['Setting']['value'] = serialize($this->data['Setting']['value']);
		}
		return true;
	}

}