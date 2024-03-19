<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%products}}`.
 */
class m240319_133808_create_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'customer_id' => $this->integer()->notNull(),
            'photo' => $this->string()->notNull(),
            'active' => "ENUM('active', 'inactive') NOT NULL DEFAULT 'active'",
        ]);

        // Adiciona a chave estrangeira para customer_id
        $this->addForeignKey(
            'fk-products-customer_id',
            'products',
            'customer_id',
            'customers',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Remove a chave estrangeira
        $this->dropForeignKey('fk-products-customer_id', 'products');

        $this->dropTable('{{%products}}');
    }
}

