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
		$model = DynamicActiveRecord::model('disk');

		if ( isset($_GET['disk']) )
			$model->attributes = $_GET['disk'];


		$model = $model->findByPk(1);


		//$model = $model->findAll();

    //$model = DynamicModel::model('disk');

//        $model = new DynamicModel('disk');
//		$models = $model->findAll('price > 10');

//		exit;
//
//        $value = array(
//            'alias'=>"asd",
//            'price'=>"10.50",
//            'name'=>"10.51",
//            'category'=>array(1),
//            'manufacturer'=>array(1)
//        );
//
//		$model = $model->findByPk(25);
//
//        $model->setAttributes($value);
//
//        $model->save();
//
//        echo "<pre>";
//
//        print_r( $model->getAttributes() );
//
//        print_r( $model->getErrors() );

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