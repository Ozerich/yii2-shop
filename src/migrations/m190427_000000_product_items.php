<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190427_000000_product_items
 */
class m190427_000000_product_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'hidden', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('{{%products}}', 'label', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'hidden');
        $this->dropColumn('{{%products}}', 'label');
    }
}
