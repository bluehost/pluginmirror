<?php
/**
 * The Setting model works very differently from any typical CakePHP models, so
 * this custom form helper overrides the default CakePHP form helper behavior
 * in a way that makes it usable with the Setting model and it's controller.
 *
 * @copyright     Copyright 2013 Bluehost (http://www.bluehost.com/)
 * @package       app.View.Helper
 * @license       GNU General Public License (http://www.gnu.org/licenses/gpl-2.0.txt)
 */

App::uses('AppFormHelper', 'View/Helper');

/**
 * Automatic generation of HTML FORMs specifically for the Setting model
 * which works very differently from other models for fields.
 *
 * @package app.View.Helper
 */
class SettingsFormHelper extends AppFormHelper
{

	/**
	 * Sets this helper's model and field properties to the dot-separated value-pair in $entity.
	 *
	 * @param string $entity A field name, like "ModelName.fieldName" or "ModelName.ID.fieldName"
	 * @param boolean $setScope Sets the view scope to the model specified in $tagValue
	 * @return void
	 */
	public function setEntity($entity, $setScope = false)
	{
		if ($entity === null) {
			$this->_modelScope = false;
		}
		if ($setScope === true) {
			$this->_modelScope = $entity;
		}
		$parts = array_values(Hash::filter(explode('.', $entity)));
		if (empty($parts)) {
			return;
		}

		if ($parts[0] != '_Token') {
			$entity = $this->_modelScope . '.' . $entity;
		}

		$this->_association = null;
		$this->_entityPath = $entity;
	}

	/**
	 * Returns false if given form field described by the current entity has no errors.
	 * Otherwise it returns the validation message
	 *
	 * @return mixed Either false when there are no errors, or an array of error
	 *    strings. An error string could be ''.
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::tagIsInvalid
	 */
	public function tagIsInvalid() {
		$entity = $this->entity();
		$model = array_shift($entity);
		$errors = array();
		if (!empty($entity) && isset($this->validationErrors[$model])) {
			$errors = $this->validationErrors[$model];
		}
		if (!empty($entity) && empty($errors)) {
			$errors = $this->_introspectModel($model, 'errors');
		}
		if (empty($errors) || empty($errors[implode('.', $entity)])) {
			return false;
		}
		$errors = $errors[implode('.', $entity)];
		return $errors === null ? false : $errors;
	}

}