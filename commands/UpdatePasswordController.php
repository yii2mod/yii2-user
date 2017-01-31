<?php

namespace yii2mod\user\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii2mod\user\models\UserModel;

/**
 * Class UpdatePasswordController
 *
 * @package yii2mod\user\commands
 */
class UpdatePasswordController extends Controller
{
    /**
     * Updates user's password to given.
     *
     * @param $email
     * @param $password
     *
     * @return int
     */
    public function actionIndex($email, $password)
    {
        $user = UserModel::findByEmail($email);

        if ($user === null) {
            $this->stdout(Yii::t('yii2mod.user', 'User is not found.') . "\n", Console::FG_RED);
        } else {
            if ($user->resetPassword($password)) {
                $this->stdout(Yii::t('yii2mod.user', 'Password has been changed.') . "\n", Console::FG_GREEN);
            } else {
                $this->stdout(Yii::t('yii2mod.user', 'Error occurred while changing password.') . "\n", Console::FG_RED);
            }
        }

        return self::EXIT_CODE_NORMAL;
    }
}
