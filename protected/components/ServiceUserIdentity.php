<?php

class ServiceUserIdentity extends CUserIdentity {
	// Номер который ищем
    protected $_id;

	// Переопределили конструктор
	function __construct( $id )
	{
		$this->_id = $id;
	}

	//
	public function getId()
	{
		return $this->_id;
	}

    // Данный метод вызывается один раз при аутентификации пользователя.
    public function authenticate()
    {
        $user = Users::model()->findByPk( $this->_id );
		
		if( $user===null ) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
			$this->username = $user->UserName ? $user->UserName : $user->Email ;

			// храним дополнительные данные для пользователя в сесии
			$this->setState('RoleID', $user->RoleID );
			$this->setState('Role', Roles::getRoleString($user->RoleID) );

			$this->errorCode = self::ERROR_NONE;
        }
       return !$this->errorCode;
    }

}