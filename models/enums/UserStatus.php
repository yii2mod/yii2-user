<?php

namespace yii2mod\user\models\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class UserStatus
 *
 * @package yii2mod\user\models\enums
 */
class UserStatus extends BaseEnum
{
    const ACTIVE = 1;
    const DELETED = 0;

    /**
     * @var string message category
     */
    public static $messageCategory = 'yii2mod.user';

    /**
     * @var array
     */
    public static $list = [
        self::ACTIVE => 'Active',
        self::DELETED => 'Deleted',
    ];
}
