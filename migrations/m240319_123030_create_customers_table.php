<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer}}`.
 */
class m240319_123030_create_customers_table extends Migration
{
     /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customers}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'cpf' => $this->string()->notNull()->unique(),
            'address' => $this->text()->notNull(),
            'photo' => $this->string()->notNull(),
            'gender' => $this->string()->notNull(),
            'active' => "ENUM('active', 'inactive') NOT NULL DEFAULT 'active'",
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customer}}');
    }
}
