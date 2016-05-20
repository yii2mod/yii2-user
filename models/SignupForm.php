<?php

namespace yii2mod\user\models;

use yii\base\Model;
use Yii;

/**
 * Class SignupForm
 * @package yii2mod\user\models
 */
class SignupForm extends Model
{
    /**
     * @var string username
     */
    public $username;

    /**
     * @var string email
     */
    public $email;

    /**
     * @var string password
     */
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => BaseUserModel::className(), 'message' => Yii::t('app', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => BaseUserModel::className(), 'message' => Yii::t('app', 'This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return BaseUserModel|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new BaseUserModel();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->lastLogin = time();
            if ($user->save()) {
                $userDetailsModels = new BaseUserDetailsModel(['userId' => $user->getId()]);
                $userDetailsModels->save();
            }
            return $user;
        }

        return null;
    }
}
