<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190407_000001_fix_categories
 */
class m190407_000001_fix_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_display}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'position' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('category_display_parent', '{{%category_display}}', 'parent_id', '{{%categories}}', 'id', 'CASCADE');
        $this->addForeignKey('category_display_category', '{{%category_display}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('category_display_parent', '{{%category_display}}');
        $this->dropForeignKey('category_display_category', '{{%category_display}}');

        $this->dropTable('{{%category_display}}');
    }
}
