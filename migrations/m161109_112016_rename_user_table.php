<?php

use yii\db\Migration;

class m161109_112016_rename_user_table extends Migration
{
    public function up()
    {
        if (Yii::$app->db->schema->getTableSchema('user') === null) {
            $this->renameTable('{{%User}}', '{{%user}}');
        }
    }

    public function down()
    {
        if (Yii::$app->db->schema->getTableSchema('User') === null) {
            $this->renameTable('{{%user}}', '{{%User}}');
        }
    }
}
