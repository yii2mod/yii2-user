<?php

namespace yii2mod\user\traits;

use yii\base\Model;
use yii2mod\user\events\FormEvent;

/**
 * Class EventTrait
 * @package yii2mod\user\traits
 */
trait EventTrait
{
    /**
     * @param Model $form
     * @return object
     */
    protected function getFormEvent(Model $form)
    {
        return \Yii::createObject(['class' => FormEvent::className(), 'form' => $form]);
    }
}