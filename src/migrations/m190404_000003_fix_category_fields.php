<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190404_0000023fix_category_fields
 */
class m190404_000003_fix_category_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%category_conditions}}', 'field_id', $this->integer());
        $this->addForeignKey('category_conditions_field', '{{%category_conditions}}', 'field_id', '{{%fields}}', 'id');

        $this->addColumn('{{%category_conditions}}', 'compare', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%category_conditions}}', 'compare');

        $this->dropForeignKey('category_conditions_field', '{{%category_conditions}}');
        $this->dropColumn('{{%category_conditions}}', 'field_id');
    }
}
