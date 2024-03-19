<?php

use yii\db\Migration;


class m240319_155630_add_users_token extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'user_token', $this->string()->unique());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'user_token');
    }

}
