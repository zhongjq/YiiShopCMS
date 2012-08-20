<?php

/**
 * This is the model class for table "Users".
 *
 * The followings are the available columns in table 'Users':
 * @property integer $ID
 * @property integer $Status
 * @property integer $RoleID
 * @property string $RegistrationDateTime
 * @property string $Email
 * @property string $Password
 */
class User extends CActiveRecord
{
    // повтор пароля при регистрации
	public $passwordRepeat;
	// капча
    public	$verifyCode;
    // роль
	public $role;
	// Запомнить
	public $remember;
	// Язык пользователя
	public $language;

	public function __construct($scenario = 'insert') {
		parent::__construct($scenario);

		if(isset(Yii::app()->request->cookies['language']))
			$this->language = Yii::app()->request->cookies['language']->value;
	}

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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			// Логин и пароль - обязательные поля
			array('email, password', 'required', 'on' => 'login, registration'),
			array('password', 'required', 'on' => 'passwordedit'),
			array('email', 'required', 'on' => 'edit'),
            array('email', 'email', 'on' => 'login, registration, edit'),
			// Длина логина должна быть в пределах от 5 до 30 символов
			array('username', 'length', 'min'=>3, 'max'=>30),
			// Статус, роль, и сервис цифры
			array('status, role_id, remember, language', 'numerical', 'integerOnly' => true, 'min'=> 0 ),
		    // Логин должен быть уникальным
			array('email', 'unique', 'on'=>'editemail, registration'),
			// Длина пароля не менее 6 символов
			array('password', 'length', 'min'=>6, 'max'=>30, 'on'=>'registration, passwordedit'),
			// проверка пароля нашим методом authenticate
			array('password', 'authenticate', 'on' => 'login'),
			// Повторный пароль обязательны для сценария регистрации
			array('passwordRepeat, verifyCode', 'required', 'on'=>'registration, passwordedit' ),
			// Длина повторного пароля не менее 6 символов
			array('passwordRepeat, verifyCode', 'length', 'min'=>6, 'max'=>30),
			// Пароль должен совпадать с повторным паролем для сценария регистрации
			array('password', 'compare', 'compareAttribute'=>'PasswordRepeat', 'on'=>'registration, passwordedit'),

			// Время регистрации пользователя
			array('registration_time', 'default','value'=>new CDbExpression('NOW()'),'on'=>'registration'),

			// Капча
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
			'id'					=> 'ID',
			'status'				=>	Yii::t('users','Status'),
			'roleID'				=>	Yii::t('users','Role'),
			'registration_time'	    =>	Yii::t('users','Date/time registration'),
			'email'					=>	Yii::t('users','E-mail'),
			'password'				=>	Yii::t('users','Password'),
			'username'				=>	Yii::t('users','Username'),
			'remember'				=>	Yii::t('users','Remember'),
			'language'				=>	Yii::t('users','Language'),
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
			$identity = new UserIdentity($this->email, $this->password);
			// Выполняем метод authenticate (о котором мы с вами говорили пару абзацев назад)
			// Он у нас проверяет существует ли такой пользовать и возвращает ошибку (если она есть)
			// в $identity->errorCode
			$identity->authenticate();

			// Теперь мы проверяем есть ли ошибка..
			switch($identity->errorCode)
			{
				case UserIdentity::ERROR_NONE:
					$duration =	$this->remember ? 3600*24*30 : 0; // 30 days
					Yii::app()->user->login($identity, $duration);

					// присваиваем язык пользователя
					if ( $this->language ){
						$cookie = new CHttpCookie('language', Languages::$Languages[$this->language]['value']);
						$cookie->expire = $duration;
						Yii::app()->request->cookies['language'] = $cookie;
					}

				break;
				case UserIdentity::ERROR_USERNAME_INVALID:
					// Если логин был указан наверно - создаем ошибку
					$this->addError('Password','Введено неправильная электронная почта или пароль.');
				break;
				case UserIdentity::ERROR_PASSWORD_INVALID:
					// Если пароль был указан наверно - создаем ошибку
					$this->addError('Password','Вы указали неверный пароль!');
				break;

			}
		}
	}

	public function md5Password(){
		if ( !empty($this->Password) )
			$this->Password = md5($this->Password);
	}

    // форма в формате CForm
    public function getArrayLoginCForm(){
    	return array(
			'attributes' => array(
				'enctype' => 'application/form-data',
				'class' => 'well',
				'id'=>'loginForm',
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => false,
				'enableClientValidation' => false,
				'id' => "loginForm",
				'clientOptions' => array(
					'validateOnSubmit' => false,
					'validateOnChange' => false,
				),
			),

			'title' => Yii::t("users",'Login'),

    		'elements'=>array(
    			'email'=>array(
    				'type'=>'text',
    				'maxlength'=>255
    			),
    			'password'=>array(
    				'type'=>'password',
    				'maxlength'=>255
    			),
				'language'=>array(
					'type'  =>  'dropdownlist',
					'items' =>  Languages::getLanguagesList(),
					'empty'=>  '',
				),
				'remember'=>array(
        			'type'=>'checkbox',
    				'layout'=>'{input}{label}{error}{hint}',
    			),
    		),
    		'buttons'=>array(
				'<br/>',
				'submit'=>array(
					'type'  =>  'submit',
					'label' =>  Yii::t("users",'Login'),
					'class' =>  "btn"
				),
			),
        );
	}
}