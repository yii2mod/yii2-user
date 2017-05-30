<?php

namespace yii2mod\user\models;

use Yii;
use yii\base\Model;
use yii2mod\user\models\enums\UserStatus;

/**
 * Class PasswordResetRequestForm
 *
 * @package yii2mod\user\models
 */
class PasswordResetRequestForm extends Model
{
    /**
     * @var string email field for password reset
     */
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => Yii::$app->user->identityClass,
                'message' => Yii::t('yii2mod.user', 'User with this email is not found.'),
            ],
            ['email', 'exist',
                'targetClass' => Yii::$app->user->identityClass,
                'filter' => ['status' => UserStatus::ACTIVE],
                'message' => Yii::t('yii2mod.user', 'Your account has been deactivated, please contact support for details.'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('yii2mod.user', 'Email'),
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        $user = UserModel::findOne(['status' => UserStatus::ACTIVE, 'email' => $this->email]);

        if (!empty($user)) {
            $user->generatePasswordResetToken();
            if ($user->save()) {
                return Yii::$app->mailer->compose('passwordResetToken', ['user' => $user])
                    ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject(Yii::t('yii2mod.user', 'Password reset for {0}', Yii::$app->name))
                    ->send();
            }
        }

        return false;
    }
}
