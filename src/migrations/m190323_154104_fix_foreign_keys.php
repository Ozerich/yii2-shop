<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190323_154104_fix_foreign_keys
 */
class m190323_154104_fix_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('product_prices_product', '{{%product_prices}}');
        $this->addForeignKey('product_prices_product', '{{%product_prices}}', 'product_id', '{{%products}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
