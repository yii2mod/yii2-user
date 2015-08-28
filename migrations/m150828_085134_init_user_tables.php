<?php

use yii\db\Migration;
use yii\db\Schema;

class m150828_085134_init_user_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        //Create user table
        $this->createTable('{{%User}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'authKey' => Schema::TYPE_STRING . '(32) NOT NULL',
            'passwordHash' => Schema::TYPE_STRING . ' NOT NULL',
            'passwordResetToken' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 10',
            'createdAt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updatedAt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'lastLogin' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        //Create user details table
        $this->createTable('{{%UserDetails}}', [
            'userId' => Schema::TYPE_PK,
            'FOREIGN KEY (userId) REFERENCES {{%User}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%User}}');
        $this->dropTable('{{%UserDetails}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
