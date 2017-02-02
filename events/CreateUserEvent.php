<?php

namespace yii2mod\user\events;

use yii\base\Event;
use yii2mod\user\models\UserModel;

/**
 * Class CreateUserEvent
 *
 * @package yii2mod\user\events
 */
class CreateUserEvent extends Event
{
    /**
     * @var UserModel
     */
    private $_user;

    /**
     * @return UserModel
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param UserModel $user
     */
    public function setUser(UserModel $user)
    {
        $this->_user = $user;
    }
}
