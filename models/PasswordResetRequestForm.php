<?php

namespace yii2mod\user\models;

use app\models\UserModel;
use yii\base\Model;

/**
 * Class PasswordResetRequestForm
 * @package yii2mod\user\models
 */
class PasswordResetRequestForm extends Model
{

    /**
     * @var string email field for password reset.
     */
    public $email;

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => \Yii::$app->user->identityClass,
                'message' => 'User with this email is not found.'
            ],
            ['email', 'exist',
                'targetClass' => \Yii::$app->user->identityClass,
                'filter' => ['status' => UserModel::STATUS_ACTIVE],
                'message' => 'Your account has been deactivated, please contact support for details.'
            ]
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = UserModel::findOne([
            'status' => UserModel::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            $user->generatePasswordResetToken();
            if ($user->save()) {
                return \Yii::$app->mail->compose('passwordResetToken', ['user' => $user])
                    ->setFrom(\Yii::$app->params['adminEmail'])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . \Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}
