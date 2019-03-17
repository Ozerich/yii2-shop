<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190316_155423_fix_prices
 */
class m190316_155423_fix_prices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_prices}}', 'param_value_second_id', $this->integer());

        $this->addForeignKey('product_prices_value_second', '{{%product_prices}}', 'param_value_second_id', '{{%product_price_param_values}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_prices_value_second', '{{%product_prices}}');
        $this->dropColumn('{{%product_prices}}', 'param_value_second_id');
    }
}
