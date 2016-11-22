<?php

namespace yii2mod\user\actions;

use Yii;

/**
 * Class LoginAction
 *
 * @package yii2mod\user\actions
 */
class LogoutAction extends Action
{
    /**
     * Logs out the current user.
     *
     * @return string
     */
    public function run()
    {
        Yii::$app->user->logout();

        return $this->redirectTo(Yii::$app->getHomeUrl());
    }
}
