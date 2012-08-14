<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
    // Будем хранить id.
    protected $_id;
    protected $Email;
    protected $Password;

	public function __construct($Email,$Password)
	{
		$this->Email = $Email;
		$this->Password = $Password;
	}

    // Данный метод вызывается один раз при аутентификации пользователя.
    public function authenticate()
    {

        // Производим стандартную аутентификацию, описанную в руководстве.
        $user = Users::model()->find('LOWER(Email)= ?', array(strtolower($this->Email)));
		
		if( ($user===null) or (md5($this->Password)!==$user->Password) ) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            $this->_id = $user->ID;

            $this->username = $user->UserName ? $user->UserName : $user->Email ;

			$this->setState('RoleID', $user->RoleID );
			$this->setState('Role', Roles::getRoleString($user->RoleID) );

			$this->errorCode = self::ERROR_NONE;
        }
       return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }

}