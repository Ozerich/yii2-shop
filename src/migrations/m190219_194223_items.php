<?php

use yii\db\Migration;

/**
 * Class m190219_194223_items
 */
class m190219_194223_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->createTable('{{%products}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'url_alias' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'image_id' => $this->integer()
        ]);

        $this->addForeignKey('product_category', '{{%products}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');
        $this->addForeignKey('product_image', '{{%products}}', 'image_id', '{{%files}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_image', '{{%products}}');
        $this->dropForeignKey('product_category', '{{%products}}');

        $this->dropTable('{{%products}}');
    }
}
