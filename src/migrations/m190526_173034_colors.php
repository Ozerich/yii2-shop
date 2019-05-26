<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190520_173034_collections
 */
class m190526_173034_colors extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%colors}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'color' => $this->string(),
            'image_id' => $this->integer()
        ]);
        $this->addForeignKey('color_image', '{{%colors}}', 'image_id', '{{%files}}', 'id', 'SET NULL');

        $this->addColumn('{{%product_images}}', 'color_id', $this->integer());
        $this->addForeignKey('product_images_color', '{{%product_images}}', 'color_id', '{{%colors}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_images_color', '{{%product_images}}');
        $this->dropColumn('{{%product_images}}', 'color_id');

        $this->dropForeignKey('color_image', '{{%colors}}');
        $this->dropTable('{{%colors}}');
    }
}
