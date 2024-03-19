<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%customers}}`.
 */
class m240319_165253_add_additional_columns_to_customers_table extends Migration
{
    public function up()
{
    $this->addColumn('customers', 'zip', $this->string(10)->notNull());
    $this->addColumn('customers', 'number', $this->string(10)->notNull());
    $this->addColumn('customers', 'city', $this->string()->notNull());
    $this->addColumn('customers', 'state', $this->string(2)->notNull());
    $this->addColumn('customers', 'complement', $this->string());
}

public function down()
{
    $this->dropColumn('customers', 'zip');
    $this->dropColumn('customers', 'number');
    $this->dropColumn('customers', 'city');
    $this->dropColumn('customers', 'state');
    $this->dropColumn('customers', 'complement');
}


}
