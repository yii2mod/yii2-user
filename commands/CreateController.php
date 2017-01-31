<?php

namespace yii2mod\user\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii2mod\user\models\UserModel;

/**
 * Class CreateController
 *
 * @package yii2mod\user\commands
 */
class CreateController extends Controller
{
    /**
     * This command creates a new user.
     *
     * @param $email
     * @param $username
     * @param $password
     */
    public function actionIndex($email, $username, $password)
    {
        $user = Yii::createObject([
            'class' => UserModel::class,
            'scenario' => 'create',
            'email' => $email,
            'username' => $username,
            'plainPassword' => $password,
        ]);

        if ($user->create()) {
            $this->stdout(Yii::t('yii2mod.user', 'User has been created.') . "!\n", Console::FG_GREEN);
        } else {
            $this->stdout(Yii::t('yii2mod.user', 'Please fix the following errors:') . "\n", Console::FG_RED);
            foreach ($user->errors as $errors) {
                foreach ($errors as $error) {
                    $this->stdout(' - ' . $error . "\n", Console::FG_RED);
                }
            }
        }
    }
}
