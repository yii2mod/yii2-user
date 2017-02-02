<?php

namespace yii2mod\user\traits;

use Yii;
use yii\base\Model;
use yii2mod\user\events\CreateUserEvent;
use yii2mod\user\events\FormEvent;
use yii2mod\user\models\UserModel;

/**
 * Class EventTrait
 *
 * @package yii2mod\user\traits
 */
trait EventTrait
{
    /**
     * @param Model $form
     *
     * @return object
     */
    protected function getFormEvent(Model $form)
    {
        return Yii::createObject(['class' => FormEvent::class, 'form' => $form]);
    }

    /**
     * @param UserModel $user
     *
     * @return object
     */
    protected function getCreateUserEvent(UserModel $user)
    {
        return Yii::createObject(['class' => CreateUserEvent::class, 'user' => $user]);
    }
}
