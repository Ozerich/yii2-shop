<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190404_000001_fix_category_types
 */
class m190404_000001_fix_category_types extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'type', $this->string()->notNull());
        $this->update('{{%categories}}', ['type' => 'CATALOG']);

        $this->createTable('{{%category_conditions}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'value' => $this->string()->notNull()
        ]);

        $this->addForeignKey('category_conditions_category', '{{%category_conditions}}', 'category_id', '{{%categories}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('category_conditions_category', '{{%category_conditions}}');
        $this->dropTable('{{%category_conditions}}');

        $this->dropColumn('{{%categories}}', 'type');
    }
}
