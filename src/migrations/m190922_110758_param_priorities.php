<?php

namespace ozerich\shop\migrations;


use yii\db\Migration;

/**
 * Class m190922_110758_param_priorities
 */
class m190922_110758_param_priorities extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_price_params}}', 'priority', $this->integer()->notNull());

        $items = $this->db->createCommand('SELECT * FROM product_price_params')->queryAll();
        $map = [];
        foreach ($items as $item) {
            if (!isset($map[$item['product_id']])) {
                $map[$item['product_id']] = 0;
            }
            $map[$item['product_id']]++;
            $this->update('{{%product_price_params}}', ['priority' => $map[$item['product_id']]], 'id=:id', [':id' => $item['id']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_price_params}}', 'priority');
    }
}
