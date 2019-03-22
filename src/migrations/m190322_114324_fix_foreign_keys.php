<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190322_114324_fix_foreign_keys
 */
class m190322_114324_fix_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('product_price_params_product', '{{%product_price_params}}');
        $this->addForeignKey('product_price_params_product', '{{%product_price_params}}', 'product_id', '{{%products}}', 'id', 'CASCADE');

        $this->dropForeignKey('product_prices_value_second', '{{%product_prices}}');
        $this->addForeignKey('product_prices_value_second', '{{%product_prices}}', 'param_value_second_id', '{{%product_price_param_values}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
