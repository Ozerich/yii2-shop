<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190328_000000_param_value_priority
 */
class m190328_000000_param_value_priority extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_price_param_values}}', 'priority', $this->integer()->notNull()->defaultValue(0));

        $map = [];
        $items = $this->getDb()->createCommand('SELECT id, product_price_param_id FROM product_price_param_values')->queryAll();
        foreach ($items as $item) {
            if (!isset($map[$item['product_price_param_id']])) {
                $map[$item['product_price_param_id']] = 0;
            }
            $map[$item['product_price_param_id']]++;

            $this->update('{{%product_price_param_values}}', ['priority' => $map[$item['product_price_param_id']]], ['id' => $item['id']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_price_param_values}}', 'priority');
    }
}
