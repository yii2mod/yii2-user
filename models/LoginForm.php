<?php

namespace yii2mod\user\models;

use Yii;
use yii\base\Model;

/**
 * Login Form
 * @package yii2mod\user\models
 */
class LoginForm extends Model
{
    /**
     * @var string email
     */
    public $email;

    /**
     * @var string password
     */
    public $password;

    /**
     * @var bool remember me
     */
    public $rememberMe = true;

    /**
     * @var bool|BaseUserModel
     */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user && $user->status === BaseUserModel::STATUS_DELETED) {
                $this->addError('password', Yii::t('app', 'Your account has been deactivated, please contact support for details.'));
            } elseif (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', Yii::t('app', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[email]]
     *
     * @return BaseUserModel|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = BaseUserModel::findByEmail($this->email);
        }
        return $this->_user;
    }
}
