<?php

namespace yii2mod\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "UserDetails".
 *
 * @property integer $userId
 */
class BaseUserDetailsModel extends ActiveRecord
{
    /**
     * Declares the name of the database table associated with this AR class.
     */
    public static function tableName()
    {
        return '{{%UserDetails}}';
    }

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     */
    public function rules()
    {
        return [
            [['userId'], 'required'],
            [['userId'], 'integer']
        ];
    }

    /**
     * Returns the text label for the specified attribute.
     * If the attribute looks like `relatedModel.attribute`, then the attribute will be received from the related model.
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('app', 'User ID'),
        ];
    }

}
