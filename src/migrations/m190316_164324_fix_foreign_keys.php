<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190316_164324_fix_foreign_keys
 */
class m190316_164324_fix_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('product_prices_param_value', '{{%product_prices}}');
        $this->dropForeignKey('product_prices_value_second', '{{%product_prices}}');

        $this->addForeignKey('product_prices_param_value', '{{%product_prices}}', 'param_value_id', '{{%product_price_param_values}}', 'id', 'CASCADE');
        $this->addForeignKey('product_prices_value_second', '{{%product_prices}}', 'param_value_second_id', '{{%product_price_param_values}}', 'id');

        $this->dropForeignKey('product_price_param_values_image', '{{%product_price_param_values}}');
        $this->dropForeignKey('product_price_param_values_param', '{{%product_price_param_values}}');

        $this->addForeignKey('product_price_param_values_param', '{{%product_price_param_values}}', 'product_price_param_id', '{{%product_price_params}}', 'id', 'CASCADE');
        $this->addForeignKey('product_price_param_values_image', '{{%product_price_param_values}}', 'image_id', '{{%files}}', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190316_164324_fix_foreign_keys cannot be reverted.\n";

        return false;
    }
    */
}
