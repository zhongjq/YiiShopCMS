<?php

/**
 * This is the model class for table "Users".
 *
 * The followings are the available columns in table 'Users':
 * @property integer $ID
 * @property integer $Status
 * @property integer $RoleID
 * @property string $RegistrationDateTime
 * @property integer $ServiceID
 * @property string $ServiceUserID
 * @property string $Email
 * @property string $Password
 */
class Users extends CActiveRecord
{
	public $PasswordRepeat;
	// капча
    public	$VerifyCode;	

	public $Role;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			// Логин и пароль - обязательные поля
			array('Email, Password', 'required', 'on' => 'login, registration'),
			array('Password', 'required', 'on' => 'passwordedit'),
			array('Email', 'required', 'on' => 'edit'),
			// Длина логина должна быть в пределах от 5 до 30 символов
			array('UserName', 'length', 'min'=>3, 'max'=>30),
			// Статус, роль, и сервис цифры
			array('Status, RoleID, ServiceID', 'numerical', 'integerOnly' => true, 'min'=> 0 ),
			// Идентификатора пользователя если он зарегистрирован через внешний сервис
			array('ServiceUserID', 'length', 'min'=> 3 ),
			// Логин должен быть уникальным
			array('Email', 'unique', 'on'=>'editemail, registration'),
			// Длина пароля не менее 6 символов
			array('Password', 'length', 'min'=>6, 'max'=>30, 'on'=>'registration, passwordedit'),
			// проверка пароля нашим методом authenticate
			array('Password', 'authenticate', 'on' => 'login'),
			// Повторный пароль обязательны для сценария регистрации
			array('PasswordRepeat, VerifyCode', 'required', 'on'=>'registration, passwordedit' ),
			// Длина повторного пароля не менее 6 символов
			array('PasswordRepeat, VerifyCode', 'length', 'min'=>6, 'max'=>30),
			// Пароль должен совпадать с повторным паролем для сценария регистрации
			array('Password', 'compare', 'compareAttribute'=>'PasswordRepeat', 'on'=>'registration, passwordedit'),

			// Дата время регистрации пользователя
			array('RegistrationDateTime','default','value'=>new CDbExpression('NOW()'),'on'=>'registration'),

			array(
				'VerifyCode',
				'captcha',
				// авторизованным пользователям код можно не вводить
				'allowEmpty'=>!Yii::app()->user->isGuest || !extension_loaded('gd'),
				'on'=>'registration, passwordedit'
			),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'Status' => 'Статус',
			'RoleID' => 'Роль',
			'RegistrationDateTime' => 'Дата/время регистрации',
			'ServiceID' => 'Сервис',
			'ServiceUserID' => 'Сервис идентификатор',
			'Email' => 'Email',
			'Password' => 'Пароль',
			'UserName' => 'Имя пользователя',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ID',$this->ID);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('RoleID',$this->RoleID);
		$criteria->compare('RegistrationDateTime',$this->RegistrationDateTime,true);
		$criteria->compare('ServiceID',$this->ServiceID);
		$criteria->compare('ServiceUserID',$this->ServiceUserID,true);
		$criteria->compare('Email',$this->Email,true);
		$criteria->compare('Password',$this->Password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	// Метод, который будет вызываться до сохранения данных в БД
	protected function beforeSave()
	{
		if(parent::beforeSave())
		{
			if($this->isNewRecord)
			{
				$this->RegistrationDateTime = date("Y-m-d H:i:s");
			}

			return true;
		} else
			return false;
	}

	/**
	 * Собственное правило для проверки
	 * Данный метод являеться связующем звеном с UserIdentity
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function authenticate($attribute,$params)
	{
		// Проверяем были ли ошибки в других правилах валидации.
		// если были - нет смысла выполнять проверку
		if( !$this->hasErrors() ) {
			// Создаем экземпляр класса UserIdentity
			// и передаем в его конструктор введенный пользователем логин и пароль (с формы)
			$identity = new UserIdentity($this->Email, $this->Password);
			// Выполняем метод authenticate (о котором мы с вами говорили пару абзацев назад)
			// Он у нас проверяет существует ли такой пользовать и возвращает ошибку (если она есть)
			// в $identity->errorCode
			$identity->authenticate();

			// Теперь мы проверяем есть ли ошибка..
			switch($identity->errorCode)
			{
				// Если ошибки нету:
				case UserIdentity::ERROR_NONE: {
				// Данная строчка говорит что надо выдать пользователю
				// соответствующие куки о том что он зарегистрирован, срок действий
				// у которых указан вторым параметром.

					Yii::app()->user->login($identity, 0);
				break;
				}
				case UserIdentity::ERROR_USERNAME_INVALID: {
				// Если логин был указан наверно - создаем ошибку
				$this->addError('password','Введено неправильная электронная почта или пароль.');
				break;
				}
				case UserIdentity::ERROR_PASSWORD_INVALID: {
					// Если пароль был указан наверно - создаем ошибку
					$this->addError('password','Вы указали неверный пароль!');
					break;
				}
			}
		}
	}

	public function md5Password(){
		if ( !empty($this->Password) )
			$this->Password = md5($this->Password);
	}

}