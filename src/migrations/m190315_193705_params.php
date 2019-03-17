<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190315_193705_params
 */
class m190315_193705_params extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_price_params}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'name' => $this->string()
        ]);

        $this->addForeignKey('product_price_params_product', '{{%product_price_params}}', 'product_id', '{{%products}}', 'id');

        $this->createTable('{{%product_price_param_values}}', [
            'id' => $this->primaryKey(),
            'product_price_param_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'image_id' => $this->integer(),
            'description' => $this->text()
        ]);

        $this->addForeignKey('product_price_param_values_param', '{{%product_price_param_values}}', 'product_price_param_id', '{{%product_price_params}}', 'id');
        $this->addForeignKey('product_price_param_values_image', '{{%product_price_param_values}}', 'image_id', '{{%files}}', 'id');

        $this->createTable('{{%product_prices}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'param_value_id' => $this->integer()->notNull(),
            'value' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('product_prices_product', '{{%product_prices}}', 'product_id', '{{%products}}', 'id');
        $this->addForeignKey('product_prices_param_value', '{{%product_prices}}', 'param_value_id', '{{%product_price_param_values}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_prices_param_value', '{{%product_prices}}');
        $this->dropForeignKey('product_prices_product', '{{%product_prices}}');
        $this->dropTable('{{%product_prices}}');

        $this->dropForeignKey('product_price_param_values_image', '{{%product_price_param_values}}');
        $this->dropForeignKey('product_price_param_values_param', '{{%product_price_param_values}}');
        $this->dropTable('{{%product_price_param_values}}');

        $this->dropForeignKey('product_price_params_product', '{{%product_price_params}}');
        $this->dropTable('{{%product_price_params}}');
    }
}
