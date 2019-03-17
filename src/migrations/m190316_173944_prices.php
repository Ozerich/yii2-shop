<?php

use yii\db\Migration;

/**
 * Class m190316_173944_prices
 */
class m190316_173944_prices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'is_prices_extended', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'is_prices_extended');
    }
}
