<?php

use yii\db\Migration;

/**
 * Class m190312_152331_product_images
 */
class m190312_152331_product_images extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'video', $this->string());

        $this->createTable('{{%product_images}}', [
            'id' => $this->primaryKey()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'image_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('product_images_product', '{{%product_images}}', 'product_id', '{{%products}}', 'id');
        $this->addForeignKey('product_images_image', '{{%product_images}}', 'image_id', '{{%files}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_images_image', '{{%product_images}}');
        $this->dropForeignKey('product_images_product', '{{%product_images}}');

        $this->dropTable('{{%product_images}}');

        $this->dropColumn('{{%products}}', 'video');
    }
}
