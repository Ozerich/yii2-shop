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
        $this->dropForeignKey('category_fields_category', '{{%category_fields}}');
        $this->addForeignKey('category_fields_category', '{{%category_fields}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
