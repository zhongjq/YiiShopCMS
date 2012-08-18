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
	// Запомнить
	public $Remember;
	// Язык пользователя
	public $Language;

	public function __construct($scenario = 'insert') {
		parent::__construct($scenario);

		if(isset(Yii::app()->request->cookies['language']))
			$this->Language = Yii::app()->request->cookies['language']->value;
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
			array('Status, RoleID, ServiceID, Remember, Language', 'numerical', 'integerOnly' => true, 'min'=> 0 ),
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
			'ID'					=> 'ID',
			'Status'				=>	Yii::t('users','Status'),
			'RoleID'				=>	Yii::t('users','Role'),
			'RegistrationDateTime'	=>	Yii::t('users','Date/time registration'),
			'ServiceID'				=> 'Сервис',
			'ServiceUserID'			=> 'Сервис идентификатор',
			'Email'					=>	Yii::t('users','E-mail'),
			'Password'				=>	Yii::t('users','Password'),
			'UserName'				=>	Yii::t('users','Username'),
			'Remember'				=>	Yii::t('users','Remember'),
			'Language'				=>	Yii::t('users','Language'),
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
				case UserIdentity::ERROR_NONE:
					$duration =	$this->Remember ? 3600*24*30 : 0; // 30 days
					Yii::app()->user->login($identity, $duration);

					// присваиваем язык пользователя
					if ( $this->Language ){
						$cookie = new CHttpCookie('language', Languages::$Languages[$this->Language]['value']);
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
				'id'=>'ManufacturersForm',
			),
			'activeForm' => array(
				'class' => 'CActiveForm',
				'enableAjaxValidation' => false,
				'enableClientValidation' => false,
				'id' => "ManufacturersForm",
				'clientOptions' => array(
					'validateOnSubmit' => false,
					'validateOnChange' => false,
				),
			),

			'title' => Yii::t("users",'Login'),

    		'elements'=>array(
    			'Email'=>array(
    				'type'=>'text',
    				'maxlength'=>255
    			),
    			'Password'=>array(
    				'type'=>'password',
    				'maxlength'=>255
    			),
				'Language'=>array(
					'type'  =>  'dropdownlist',
					'items' =>  Languages::getLanguagesList(),
					'empty'=>  '',
				),
				'Remember'=>array(
        			'type'=>'checkbox',
    				'layout'=>'{input}{label}{error}{hint}',
    			),
    		),
			 'ErrorSummary',
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