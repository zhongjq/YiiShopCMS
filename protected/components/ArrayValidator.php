<?php
/**
 * Original sources and separateParams example:
 * @see http://www.yiiframework.com/extension/array-validator/
 *
 * @author Marco van 't Wout, Tremani 2012
 *
 * Example usage:
 *
 * public function rules() {
 *     return array(
 *         array('numberList', 'ArrayValidator', 'validator'=>'numerical', 'params'=>array(
 *             'integerOnly'=>true, 'allowEmpty'=>false
 *         )),
 *     );
 * }
 */
class ArrayValidator extends CValidator {

	/**
	 * @var string name of the validator class (example: 'numerical' or 'CustomValidator')
	 */
	public $validator;

	/**
	 * @var array parameters passed to the validator class
	 */
	public $params;

	/**
	 * @var bool use a separate params array depending on array attribute keys
	 */
	public $separateParams = false;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

	/**
	 * @var Object the validator instance
	 */
	protected $validatorObject;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object, $attribute)
	{
		if ($this->isEmpty($object->$attribute)) {
			if ($this->allowEmpty === true) {
				$object->$attribute = null;
				return;
			}
			$this->addError($object, $attribute, Yii::t('', '{attribute} is not allowed to be empty', array('{attribute}'=>$attribute)));
			return;
		}

		if (!is_array($object->$attribute) ) {
			$this->addError($object, $attribute, Yii::t('', 'You are trying to validate a non-array attribute'));
			return;
		}

		// Create validator and set params
		$this->validatorObject = self::createValidator($this->validator, $object, array($attribute));
		if (!$this->separateParams) {
			$this->setValidatorParams($this->params);
		}

		// Loop validator for every array element
		$attributeArray = $object->$attribute; // create copy of attribute array
		foreach($attributeArray as $key => &$value) { // by reference
			$object->$attribute = $value; // temporary store single value in object attribute
			if ($this->separateParams) {
				$this->setValidatorParams($this->params[$key]);
			}
			$this->validatorObject->validate($object);
			$value = $object->$attribute; // put validated value back in attribute array
		}
		$object->$attribute = $attributeArray; // restore attribute array

		// If attribute has errors, show first error
		if ($object->hasErrors($attribute)) {
			$firstError = $object->errors[$attribute][0];
			$object->clearErrors($attribute);
			$object->addError($attribute, Yii::t('', 'At least one element has an error:').' '.$firstError);
		}
	}

	/**
	 * Set parameters for validator.
	 * @param array $params
	 */
	protected function setValidatorParams($params)
	{
		foreach($params as $paramName => $paramValue) {
			$this->validatorObject->$paramName = $paramValue;
		}
	}
}