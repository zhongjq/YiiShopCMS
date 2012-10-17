<?php

/**
 * SiteController is the default controller to handle user requests.
 * var @model CustemCActiveRecord
 */
class SiteController extends Controller
{

	public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
            ),
        );
    }


    /**
	 * Index action is the default action in a controller.
	 * var @model CustemCActiveRecord
	 */
	public function actionTest()
	{   
        $connection=Yii::app()->db;
        
        
        
        $sql="
SELECT 
`product_field`.*,
`field_tab`.`position` as `position_tab`,
`min_length`,`max_length`,
NULL as `min_value`, NULL as `max_value`,NULL as `rows`,NULL as `decimal`,NULL as `default`,NULL as list_id,NULL as is_multiple_select
FROM `product_field`
-- min_length, max_length
JOIN `string_field` ON string_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION  

SELECT 
`product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL,NULL,`min_value`,`max_value`,NULL,NULL,NULL,NULL,NULL
FROM `product_field`
-- min_value, max_value
JOIN `integer_field` ON integer_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION  

SELECT 
`product_field`.*,
`field_tab`.`position` as `position_tab`,
`min_length`,`max_length`,
NULL as `min_value`, NULL as `max_value`, 
`rows`,
NULL as `decimal`,
NULL as `default`,
NULL as list_id,
NULL as is_multiple_select
FROM `product_field`
-- row, min_length, max_length
JOIN `text_field` ON text_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION 

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, `max_value`, 
NULL as `rows`,
NULL as `decimal`,
NULL as `default`,
NULL as list_id,
NULL as is_multiple_select
FROM `product_field`
-- max_value
JOIN `price_field` ON price_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION 

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, NULL as `max_value`, 
NULL as `rows`,
`decimal`,
NULL as `default`,
NULL as list_id,
NULL as is_multiple_select
FROM `product_field`
-- decimal
JOIN `double_field` ON double_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION 

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, NULL as `max_value`, 
NULL as `rows`,
NULL as `decimal`,
`default`,
NULL as list_id,
NULL as is_multiple_select
FROM `product_field`
-- default
JOIN `boolean_field` ON boolean_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id

UNION 

SELECT `product_field`.*,
`field_tab`.`position` as `position_tab`,
NULL as `min_length`, NULL as `max_length`,
NULL as `min_value`, NULL as `max_value`, 
NULL as `rows`,
NULL as `decimal`,
NULL as `default`,
list_id,
is_multiple_select
FROM `product_field`
-- list_id, is_multiple_select
JOIN `list_field` ON list_field.field_id = id
LEFT JOIN `field_tab` ON `field_tab`.field_id = id
WHERE `product_id` = :product_id
        
";
        
        
        $command = $connection->createCommand($sql);
        $command->bindValue(":product_id",1,PDO::PARAM_STR);
       
        $users = $command->setFetchMode(PDO::FETCH_OBJ)->queryAll();     
        
        echo "<pre>";
        print_r($users);
        
        
        //$this->render('test', array('model'=> $model ));
	}

	/**
	 * Index action is the default action in a controller.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}


	public function actionError(){
		if($error=Yii::app()->errorHandler->error)
			$this->render('error', $error);
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{

		$service = Yii::app()->request->getQuery('service');

        if (isset($service)) {
            $authIdentity				= Yii::app()->eauth->getIdentity($service);
            $authIdentity->redirectUrl	= Yii::app()->user->returnUrl;
            $authIdentity->cancelUrl	= $this->createAbsoluteUrl('login');

			try {

	            if ($authIdentity->authenticate()) {
	                $identity = new EAuthUserIdentity($authIdentity);

	                if ($identity->authenticate()) {

		                $ServiceUserID  = $authIdentity->id;
		                $ServiceID      = Services::getServiceId( $authIdentity->serviceName);
						$user = Users::model()->
									find(   'ServiceID = :ServiceID AND ServiceUserID = :ServiceUserID',
											array(  ':ServiceUserID'=> $ServiceUserID,
													':ServiceID'    => $ServiceID )
									);

						if (!$user) {

							Yii::app()->session['ServiceUserID']	= $ServiceUserID;
							Yii::app()->session['ServiceID']	    = $ServiceID;
							if ( isset($authIdentity->name) )
								Yii::app()->session['UserName']		    = $authIdentity->name;
							if ( isset($authIdentity->email) )
								Yii::app()->session['Email']		    = $authIdentity->email;


							if ( isset($authIdentity->name) ) {

								$user = new Users('signup');
								$user->UserName         = $authIdentity->name;
								$user->Email	        = isset($authIdentity->email) ? $authIdentity->email : null;
								$user->ServiceUserID	= $ServiceUserID;
								$user->ServiceID		= $ServiceID;
								$user->RoleID			= Roles::USER;
								$user->Password		    = null;
								if($user->validate()) {
									if ( $user->save() ) {
										$identity= new ServiceUserIdentity($user->ID);
										$identity->authenticate();
										Yii::app()->user->login($identity,0);
										// переадресовываем
										$this->render('registration_ok');
									}
								}

							} else {
								throw new CHttpException(401,'Невозможно завершить авторизацию. Возможно, провайдер не передает достаточный набор данных для авторизации пользователя.');
							}

						} else {
							$identity= new ServiceUserIdentity($user->ID);
							$identity->authenticate();
							Yii::app()->user->login($identity,0);
						}

	                    $authIdentity->redirect();
	                }
	                else {
	                    $authIdentity->cancel();
	                }
	            }
			}
			catch (Exception $e) {
				throw new CHttpException(401,'Невозможно завершить авторизацию. Возможно, провайдер не передает достаточный набор данных для авторизации пользователя.');
			}

            $this->redirect(array('login'));
        }


        $model = new Users('login');

		if (!Yii::app()->user->isGuest) {
			throw new CHttpException(403,'Недостаточно прав!');
		} else {
			if (!empty($_POST['Users'])) {
				$model->attributes = $_POST['Users'];
				if($model->validate()) {
					$this->redirect(  Yii::app()->user->returnUrl );
				}
			}

		}

		$this->render('login', array('model'=>$model) );
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		//$this->redirect(Yii::app()->user->returnUrl);
		$this->redirect(Yii::app()->homeUrl);
	}

    public function actionSignup()
    {
        $user = new Users('signup');

		$user->UserName = Yii::app()->session['UserName'];
		$user->Email	= Yii::app()->session['Email'];

        if(isset($_POST['Users']))
        {
	        $user->attributes       = $_POST['Users'];
	        $user->Status	        = 3; // подтверждение емайл
	        $user->ServiceUserID	= Yii::app()->session['ServiceUserID'];
			$user->ServiceID		= Yii::app()->session['ServiceID'];
			$user->RoleID			= Roles::USER;
			$user->Password		    = null;

            if($user->validate())
            {
                if ( $user->save() ) {
	                $message = $this->renderPartial('/email/registration',array('code'=>$model->Password),true);

	                SendMail::send( $model->Email , 'Потверждение регистрации', $message );

	                $this->render('confirmation');
	                Yii::app()->end();
				}
            }
        }

        $this->render('signup', array('form'=>$user));
    }

	// регистрация
    public function actionRegistration()
    {
        // создаем обект с регистрацией
        $model = new Users('registration');

        if ( isset($_POST['Users']) ) {
	        $model->attributes = $_POST['Users'];
			$model->RoleID = Roles::USER;
			$model->Status = 3;

	        if($model->validate())
            {
	            $this->md5Password();
                if ($model->save(false)) {

					$message = $this->renderPartial('/email/registration',array('code'=>$model->Password),true);

					SendMail::send( $model->Email , 'Потверждение регистрации', $message );

					$this->render('confirmation');
					Yii::app()->end();
				}
            }
        }

		$this->render('registration', array('model'=>$model) );
    }

	public function actionConfirmation($code) {

		if ( !$code )
			throw new CHttpException(404,'Ошибка!');

		$User = Users::model()->find(array(
					'select'	=> 'id',
					'condition'	=> 'Status = :Status AND Password = :Code',
					'params'	=> array(':Status'=> Statuses::CONFIRM_EMAIL, ':Code' => $code),
				));


		if ( $User ) {
			$User->Status = 1;

			if ( $User->validate() ){
				$User->save();
				$this->render('registration_ok');
			} else {
				throw new CHttpException(404,'Ошибка!');
			}

		} else
			throw new CHttpException(404,'Страница не найдена!');

	}
}