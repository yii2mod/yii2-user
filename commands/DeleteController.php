<?php

namespace yii2mod\user\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii2mod\user\models\UserModel;

/**
 * Class DeleteController
 *
 * @package yii2mod\user\commands
 */
class DeleteController extends Controller
{
    /**
     * Deletes a user.
     *
     * @param string $email user email
     *
     * @return int
     */
    public function actionIndex($email)
    {
        if ($this->confirm(Yii::t('yii2mod.user', 'Are you sure you want to delete this user?'))) {
            $user = UserModel::findByEmail($email);
            if ($user === null) {
                $this->stdout(Yii::t('yii2mod.user', 'User is not found.') . "\n", Console::FG_RED);
            } else {
                if ($user->delete()) {
                    $this->stdout(Yii::t('yii2mod.user', 'User has been deleted.') . "\n", Console::FG_GREEN);
                } else {
                    $this->stdout(Yii::t('yii2mod.user', 'Error occurred while deleting user.') . "\n", Console::FG_RED);
                }
            }
        }

        return self::EXIT_CODE_NORMAL;
    }
}
