<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190520_173034_collections
 */
class m190525_173034_same extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_same}}', [
            'product_id' => $this->integer()->notNull(),
            'product_same_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('product_same_product', '{{%product_same}}', 'product_id', '{{%products}}', 'id', 'CASCADE');
        $this->addForeignKey('product_same_product_same', '{{%product_same}}', 'product_same_id', '{{%products}}', 'id', 'CASCADE');

        $this->addPrimaryKey('product_same_pk', '{{%product_same}}', ['product_id', 'product_same_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_same_product_same', '{{%product_same}}');
        $this->dropForeignKey('product_same_product', '{{%product_same}}');

        $this->dropTable('{{%product_same}}');
    }
}
