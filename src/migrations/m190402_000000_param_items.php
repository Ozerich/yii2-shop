<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190402_000000_param_items
 */
class m190402_000000_param_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'sku', $this->string());
        $this->addColumn('{{%products}}', 'sale_disabled', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('{{%products}}', 'sale_disabled_text', $this->string());
        $this->addColumn('{{%products}}', 'price_hidden', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('{{%products}}', 'price_hidden_text', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'price_hidden_text');
        $this->dropColumn('{{%products}}', 'price_hidden');
        $this->dropColumn('{{%products}}', 'sale_disabled_text');
        $this->dropColumn('{{%products}}', 'sale_disabled');
        $this->dropColumn('{{%products}}', 'sku');
    }
}
