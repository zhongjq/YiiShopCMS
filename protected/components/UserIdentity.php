<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
    // Будем хранить id.
    protected $_id;
    protected $_email;
    protected $_password;

	public function __construct($email,$password)
	{
		$this->_email = $email;
		$this->_password = $password;
	}

    // Данный метод вызывается один раз при аутентификации пользователя.
    public function authenticate()
    {

        // Производим стандартную аутентификацию, описанную в руководстве.
        $user = User::model()->find('LOWER(email)= ?', array(strtolower($this->_email)));
		
		if( ($user===null) or (md5($this->_password)!==$user->password) ) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            $this->_id = $user->id;

            $this->username = $user->username ? $user->username : $user->email ;

			$this->setState('role_id', $user->role_id );
			$this->setState('role', Roles::getRoleString($user->role_id) );

			$this->errorCode = self::ERROR_NONE;
        }
       return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }

}