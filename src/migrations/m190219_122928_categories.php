<?php

use yii\db\Migration;

/**
 * Class m190219_122928_categories
 */
class m190219_122928_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'url_alias' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('category_parent', '{{%categories}}', 'parent_id', '{{%categories}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('category_parent', '{{%categories}}');
    }
}
