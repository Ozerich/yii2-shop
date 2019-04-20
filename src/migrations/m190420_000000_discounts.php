<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190420_000000_discounts
 */
class m190420_000000_discounts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'discount_mode', $this->string());
        $this->addColumn('{{%products}}', 'discount_value', $this->float());
        $this->addColumn('{{%products}}', 'price_with_discount', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'price_with_discount');
        $this->dropColumn('{{%products}}', 'discount_value');
        $this->dropColumn('{{%products}}', 'discount_mode');
    }
}
