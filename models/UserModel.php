<?php

namespace yii2mod\user\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii2mod\user\models\enums\UserStatus;
use yii2mod\user\traits\EventTrait;

/**
 * Class UserModel
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $last_login
 * @property string $password write-only password
 */
class UserModel extends ActiveRecord implements IdentityInterface
{
    use EventTrait;

    /**
     * Event is triggered before creating a user.
     * Triggered with \yii2mod\user\events\CreateUserEvent.
     */
    const BEFORE_CREATE = 'beforeCreate';

    /**
     * Event is triggered after creating a user.
     * Triggered with \yii2mod\user\events\CreateUserEvent.
     */
    const AFTER_CREATE = 'afterCreate';

    /**
     * @var string plain password
     */
    public $plainPassword;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            ['email', 'unique', 'message' => Yii::t('yii2mod.user', 'This email address has already been taken.')],
            ['username', 'unique', 'message' => Yii::t('yii2mod.user', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['plainPassword', 'string', 'min' => 6],
            ['plainPassword', 'required', 'on' => 'create'],
            ['status', 'default', 'value' => UserStatus::ACTIVE],
            ['status', 'in', 'range' => UserStatus::getConstantsByName()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('yii2mod.user', 'Username'),
            'email' => Yii::t('yii2mod.user', 'Email'),
            'status' => Yii::t('yii2mod.user', 'Status'),
            'created_at' => Yii::t('yii2mod.user', 'Registration time'),
            'last_login' => Yii::t('yii2mod.user', 'Last login'),
            'plainPassword' => Yii::t('yii2mod.user', 'Password'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Create user
     *
     * @return null|UserModel the saved model or null if saving fails
     *
     * @throws \Exception
     */
    public function create()
    {
        $transaction = $this->getDb()->beginTransaction();

        try {
            $event = $this->getCreateUserEvent($this);
            $this->trigger(self::BEFORE_CREATE, $event);

            $this->setPassword($this->plainPassword);
            $this->generateAuthKey();

            if (!$this->save()) {
                $transaction->rollBack();

                return null;
            }

            $this->trigger(self::AFTER_CREATE, $event);

            $transaction->commit();

            return $this;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::warning($e->getMessage());
            throw $e;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user (with active status) by username
     *
     * @param  string $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => UserStatus::ACTIVE]);
    }

    /**
     * Finds user by email
     *
     * @param $email
     *
     * @return null|static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => UserStatus::ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = ArrayHelper::getValue(Yii::$app->params, 'user.passwordResetTokenExpire', 3600);

        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->getSecurity()->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->last_login = $lastLogin;
    }

    /**
     * Update last login
     */
    public function updateLastLogin()
    {
        $this->updateAttributes(['last_login' => time()]);
    }

    /**
     * Resets password.
     *
     * @param string $password
     *
     * @return bool
     */
    public function resetPassword($password)
    {
        $this->setPassword($password);

        return $this->save(true, ['password_hash']);
    }
}
