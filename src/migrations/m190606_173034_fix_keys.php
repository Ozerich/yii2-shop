<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190606_173034_fix_keys
 */
class m190606_173034_fix_keys extends Migration
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
