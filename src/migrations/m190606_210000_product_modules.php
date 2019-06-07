<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190606_210000_product_modules
 */
class m190606_210000_product_modules extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'type', $this->string()->notNull()->defaultValue('SIMPLE'));

        $this->createTable('{{%product_modules}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'priority' => $this->integer()->notNull(),
            'default_quantity' => $this->boolean()->notNull()->defaultValue(0),
            'product_value_id' => $this->integer(),
            'name' => $this->string(),
            'image_id' => $this->integer(),
            'sku' => $this->string(),
            'note' => $this->string(),
            'params' => $this->text(),
            'price' => $this->float(),
            'price_with_discount' => $this->float(),
            'currency_id' => $this->integer(),
            'discount_mode' => $this->string(),
            'discount_value' => $this->float(),
        ]);

        $this->addForeignKey('product_modules_product', '{{%product_modules}}', 'product_id', '{{%products}}', 'id', 'CASCADE');
        $this->addForeignKey('product_modules_value_product', '{{%product_modules}}', 'product_value_id', '{{%products}}', 'id', 'CASCADE');
        $this->addForeignKey('product_modules_image', '{{%product_modules}}', 'image_id', '{{%files}}', 'id', 'SET NULL');
        $this->addForeignKey('product_modules_currency', '{{%product_modules}}', 'currency_id', '{{%currencies}}', 'id', 'SET NULL');

        $this->createTable('{{%product_module_images}}', [
            'id' => $this->primaryKey(),
            'product_module_id' => $this->integer()->notNull(),
            'image_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('product_module_images_product_image', '{{%product_module_images}}', 'product_module_id', '{{%product_modules}}', 'id', 'CASCADE');
        $this->addForeignKey('product_module_images_image', '{{%product_module_images}}', 'image_id', '{{%files}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_module_images_product_image', '{{%product_module_images}}');
        $this->dropForeignKey('product_module_images_image', '{{%product_module_images}}');
        $this->dropTable('{{%product_module_images}}');

        $this->dropForeignKey('product_modules_product', '{{%product_modules}}');
        $this->dropForeignKey('product_modules_value_product', '{{%product_modules}}');
        $this->dropForeignKey('product_modules_image', '{{%product_modules}}');
        $this->dropForeignKey('product_modules_currency', '{{%product_modules}}');

        $this->dropTable('{{%product_modules}}');

        $this->dropColumn('{{%products}}', 'type');
    }
}
