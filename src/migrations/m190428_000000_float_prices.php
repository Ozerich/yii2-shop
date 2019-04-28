<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190428_000000_float_prices
 */
class m190428_000000_float_prices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%products}}', 'price', $this->float());
        $this->alterColumn('{{%product_prices}}', 'value', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
