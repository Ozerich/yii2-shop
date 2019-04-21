<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190421_000000_stock
 */
class m190421_000000_stock extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_prices}}', 'stock', $this->string());
        $this->addColumn('{{%product_prices}}', 'stock_waiting_days', $this->string());
        $this->addColumn('{{%products}}', 'stock', $this->string());
        $this->addColumn('{{%products}}', 'stock_waiting_days', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'stock_waiting_days');
        $this->dropColumn('{{%products}}', 'stock');
        $this->dropColumn('{{%product_prices}}', 'stock_waiting_days');
        $this->dropColumn('{{%product_prices}}', 'stock');
    }
}
