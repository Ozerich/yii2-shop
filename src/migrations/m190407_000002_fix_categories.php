<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190407_000002_fix_categories
 */
class m190407_000002_fix_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('category_conditions_category', '{{%category_conditions}}');
        $this->addForeignKey('category_conditions_category', '{{%category_conditions}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
