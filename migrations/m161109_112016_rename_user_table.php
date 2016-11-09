<?php

use yii\db\Migration;

class m161109_112016_rename_user_table extends Migration
{
    public function up()
    {
        $this->renameTable('{{%User}}', '{{%user}}');
    }

    public function down()
    {
        $this->renameTable('{{%user}}', '{{%User}}');
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
