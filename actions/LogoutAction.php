<?php

namespace yii2mod\user\actions;

use Yii;
use yii\base\Action;

/**
 * Class LoginAction
 * @package yii2mod\user\actions
 */
class LogoutAction extends Action
{
    /**
     * @return string
     */
    public function run()
    {
        Yii::$app->user->logout();
        return $this->controller->goHome();
    }
}