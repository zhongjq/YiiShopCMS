<?php
/**
 * Created by JetBrains PhpStorm.
 * User: EnChikiben
 * Date: 24.07.12
 * Time: 23:11
 * To change this template use File | Settings | File Templates.
 */
class ProductFieldForm extends CFormModel
{
	public $name;
	public $phone;
	public $timeToCall;

	public function rules()
	{
		return array(
			array('name, phone', 'required'),
			array('timeToCall', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'name'=>'Ваше имя',
			'phone'=>'Телефон',
			'timeToCall'=>'Время звонка',
		);
	}
}
