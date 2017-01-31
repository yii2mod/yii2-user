<?php

namespace yii2mod\user;

use yii\base\Module;

/**
 * Class ConsoleModule
 *
 * @package yii2mod\user
 */
class ConsoleModule extends Module
{
    /**
     * @var string the namespace that controller classes are in
     */
    public $controllerNamespace = 'yii2mod\user\commands';
}
