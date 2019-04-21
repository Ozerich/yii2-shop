<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190420_000000_discounts_for_extended
 */
class m190420_000000_discounts_for_extended extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_prices}}', 'discount_mode', $this->string());
        $this->addColumn('{{%product_prices}}', 'discount_value', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_prices}}', 'discount_value');
        $this->dropColumn('{{%product_prices}}', 'discount_mode');
    }
}
