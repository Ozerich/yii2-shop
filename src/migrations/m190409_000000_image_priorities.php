<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190409_000000_image_priorities
 */
class m190409_000000_image_priorities extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_images}}', 'text', $this->string());
        $this->addColumn('{{%product_images}}', 'priority', $this->integer()->notNull()->defaultValue(0));

        $map = [];
        $items = $this->db->createCommand('SELECT * FROM product_images')->queryAll();
        foreach ($items as $item) {
            if (!isset($map[$item['product_id']])) {
                $map[$item['product_id']] = 0;
            }
            $map[$item['product_id']]++;
            $this->update('{{%product_images}}', ['priority' => $map[$item['product_id']]], 'id=:id', [':id' => $item['id']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_images}}', 'priority');
        $this->dropColumn('{{%product_images}}', 'text');
    }
}
