<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190321_234500_multiple_categories
 */
class m190321_234500_multiple_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_categories}}', [
            'product_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull()
        ]);

        $this->addPrimaryKey('product_categories_pk', '{{%product_categories}}', ['product_id', 'category_id']);

        $this->addForeignKey('product_categories_product', '{{%product_categories}}', 'product_id', '{{%products}}', 'id', 'CASCADE');
        $this->addForeignKey('product_categories_category', '{{%product_categories}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');

        $items = $this->getDb()->createCommand('SELECT id, category_id FROM {{%products}}')->queryAll();
        foreach ($items as $item) {
            $this->insert('{{%product_categories}}', [
                'product_id' => $item['id'],
                'category_id' => $item['category_id']
            ]);
        }

        $this->dropForeignKey('product_category', '{{%products}}');
        $this->dropColumn('{{%products}}', 'category_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%products}}', 'category_id', $this->integer());
        $this->addForeignKey('product_category', '{{%products}}', 'category_id', '{{%categories}}', 'id');

        $this->dropForeignKey('product_categories_category', '{{%product_categories}}');
        $this->dropForeignKey('product_categories_product', '{{%product_categories}}');

        $this->dropTable('{{%product_categories}}');
    }
}
