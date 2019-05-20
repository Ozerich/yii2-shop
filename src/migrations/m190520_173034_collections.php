<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190520_173034_collections
 */
class m190520_173034_collections extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_collections}}', [
            'id' => $this->primaryKey(),
            'url_alias' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'content' => 'LONGTEXT',
            'image_id' => $this->integer(),
            'manufacture_id' => $this->integer(),
            'seo_title' => $this->string(),
            'seo_description' => $this->text(),
        ]);

        $this->addForeignKey('product_collections_image', '{{%product_collections}}', 'image_id', '{{%files}}', 'id', 'SET NULL');
        $this->addForeignKey('product_collections_manufacture', '{{%product_collections}}', 'manufacture_id', '{{%manufactures}}', 'id', 'SET NULL');

        $this->addColumn('{{%products}}', 'collection_id', $this->integer());
        $this->addForeignKey('product_collection', '{{%products}}', 'collection_id', '{{%product_collections}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_collection', '{{%products}}');
        $this->dropColumn('{{%products}}', 'collection_id');

        $this->dropForeignKey('product_collections_image', '{{%product_collections}}');
        $this->dropForeignKey('product_collections_manufacture', '{{%product_collections}}');
        $this->dropTable('{{%product_collections}}');
    }
}
