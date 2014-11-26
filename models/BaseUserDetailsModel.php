<?php

namespace yii2mod\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "UserDetails".
 *
 * @property integer $userId
 *
 * @property User $user
 */
class BaseUserDetailsModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'UserDetails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('user', 'User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserModel::className(), ['id' => 'userId']);
    }
}
