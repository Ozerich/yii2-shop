<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190322_154104_fix_foreign_keys
 */
class m190322_154104_fix_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('product_field_values_product', '{{%product_field_values}}');
        $this->dropForeignKey('product_field_values_field', '{{%product_field_values}}');
        $this->dropForeignKey('product_images_product', '{{%product_images}}');

        $this->addForeignKey('product_field_values_product', '{{%product_field_values}}', 'product_id', '{{%products}}', 'id', 'CASCADE');
        $this->addForeignKey('product_field_values_field', '{{%product_field_values}}', 'field_id', '{{%fields}}', 'id', 'CASCADE');
        $this->addForeignKey('product_images_product', '{{%product_images}}', 'product_id', '{{%products}}', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
