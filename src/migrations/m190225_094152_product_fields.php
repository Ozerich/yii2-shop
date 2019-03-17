<?php

use yii\db\Migration;

/**
 * Class m190225_094152_product_fields
 */
class m190225_094152_product_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_field_values}}', [
            'product_id' => $this->integer()->notNull(),
            'field_id' => $this->integer()->notNull(),
            'value' => $this->text()->notNull()
        ]);

        $this->addPrimaryKey('product_field_values_pk', '{{%product_field_values}}', ['product_id', 'field_id']);
        $this->addForeignKey('product_field_values_product', '{{%product_field_values}}', 'product_id', '{{%products}}', 'id');
        $this->addForeignKey('product_field_values_field', '{{%product_field_values}}', 'field_id', '{{%fields}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_field_values_product', '{{%product_field_values}}');
        $this->dropForeignKey('product_field_values_field', '{{%product_field_values}}');
        $this->dropPrimaryKey('product_field_values_pk', '{{%product_field_values}}');

        $this->dropTable('{{%product_field_values}}');
    }
}
