<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190426_000000_currencies
 */
class m190426_000000_currencies extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currencies}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'full_name' => $this->string()->notNull(),
            'rate' => $this->float(),
            'primary' => $this->boolean()->notNull()->defaultValue(false)
        ]);

        $this->insert('{{%currencies}}', [
            'name' => 'у.е',
            'full_name' => 'Базовая валюта',
            'rate' => 1.0,
            'primary' => true
        ]);

        $this->addColumn('{{%products}}', 'currency_id', $this->integer());
        $this->addForeignKey('product_currency', '{{%products}}', 'currency_id', '{{%currencies}}', 'id', 'SET NULL');

        $this->addColumn('{{%product_prices}}', 'currency_id', $this->integer());
        $this->addForeignKey('product_prices_currency', '{{%product_prices}}', 'currency_id', '{{%currencies}}', 'id', 'SET NULl');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_prices_currency', '{{%product_prices}}');
        $this->dropColumn('{{%product_prices}}', 'currency_id');

        $this->dropForeignKey('product_currency', '{{%products}}');
        $this->dropColumn('{{%products}}', 'currency_id');

        $this->dropTable('{{%currencies}}');
    }
}
