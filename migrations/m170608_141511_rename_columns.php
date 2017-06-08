<?php

use yii\db\Migration;

class m170608_141511_rename_columns extends Migration
{
    public function safeUp()
    {
        $this->renameColumn('{{%user}}', 'passwordHash', 'password_hash');
        $this->renameColumn('{{%user}}', 'passwordResetToken', 'password_reset_token');
        $this->renameColumn('{{%user}}', 'authKey', 'auth_key');
        $this->renameColumn('{{%user}}', 'lastLogin', 'last_login');
        $this->renameColumn('{{%user}}', 'createdAt', 'created_at');
        $this->renameColumn('{{%user}}', 'updatedAt', 'updated_at');
    }

    public function safeDown()
    {
        $this->renameColumn('{{%user}}', 'password_hash', 'passwordHash');
        $this->renameColumn('{{%user}}', 'password_reset_token', 'passwordResetToken');
        $this->renameColumn('{{%user}}', 'auth_key', 'authKey');
        $this->renameColumn('{{%user}}', 'last_login', 'lastLogin');
        $this->renameColumn('{{%user}}', 'created_at', 'createdAt');
        $this->renameColumn('{{%user}}', 'updated_at', 'updatedAt');
    }
}
