<?php

namespace yii2mod\user\models;

use Yii;
use yii\base\Model;

/**
 * Class SignupForm
 *
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
     * @var UserModel
     */
    protected $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => UserModel::class, 'message' => Yii::t('yii2mod.user', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => UserModel::class, 'message' => Yii::t('yii2mod.user', 'This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('yii2mod.user', 'Username'),
            'email' => Yii::t('yii2mod.user', 'Email'),
            'password' => Yii::t('yii2mod.user', 'Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return UserModel|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $this->user = new UserModel();
        $this->user->setAttributes($this->attributes);
        $this->user->setPassword($this->password);
        $this->user->setLastLogin(time());
        $this->user->generateAuthKey();

        return $this->user->save() ? $this->user : null;
    }

    /**
     * @return UserModel|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
