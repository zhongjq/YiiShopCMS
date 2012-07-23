<?php

class DefaultController extends Controller
{


	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

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

}