<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190305_191956_category_images
 */
class m190305_191956_category_images extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'image_id', $this->integer());
        $this->addForeignKey('category_image', '{{%categories}}', 'image_id', '{{%files}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('category_image', '{{%categories}}');
        $this->dropColumn('{{%categories}}', 'image_id');
    }
}
