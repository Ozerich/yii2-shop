<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190426_000001_fix_currencies
 */
class m190426_000001_fix_currencies extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('product_prices_currency', '{{%product_prices}}');
        $this->dropColumn('{{%product_prices}}', 'currency_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%product_prices}}', 'currency_id', $this->integer());
        $this->addForeignKey('product_prices_currency', '{{%product_prices}}', 'currency_id', '{{%currencies}}', 'id', 'SET NULl');
    }
}
