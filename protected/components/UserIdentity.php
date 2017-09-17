<?php

class UserIdentity extends CUserIdentity
{
    private $_id;

    public function authenticate() {
        $user = User::model()->find('LOWER(username)=?', array(strtolower($this->username)));

        if (!$user) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($user->password != ($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $user->id;
            $this->username = $user->username;
            Yii::app()->user->realname = $user->username;
            Yii::app()->user->lastlogin = $user->lastlogin ? $user->lastlogin : date('Y-m-d H:i:s');
            $this->errorCode = self::ERROR_NONE;
            //更新最后登录时间
            User::model()->updateByPk($this->_id, array('lastlogin' => date('Y-m-d H:i:s')));
        }

        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }

}