<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190320_230000_category_levels
 */
class m190320_230000_category_levels extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'level', $this->integer()->notNull()->defaultValue(0));

        $items = $this->db->createCommand('SELECT id, parent_id FROM {{%categories}}')->queryAll();

        $parents_map = [];
        foreach ($items as $item) {
            $parents_map[$item['id']] = $item['parent_id'];
        }

        foreach ($items as $item) {
            $level = 1;

            $parent = $item['parent_id'];
            while ($parent) {
                $level = $level + 1;
                $parent = $parents_map[$parent];
            }

            $this->update('{{%categories}}', ['level' => $level], 'id=:id', [':id' => $item['id']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%categories}}', 'level');
    }
}
