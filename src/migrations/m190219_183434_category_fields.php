<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190219_183434_category_fields
 */
class m190219_183434_category_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category_fields}}', [
            'category_id' => $this->integer()->notNull(),
            'field_id' => $this->integer()->notNull()
        ]);

        $this->addPrimaryKey('category_fields_pk', '{{%category_fields}}', ['category_id', 'field_id']);

        $this->addForeignKey('category_fields_category', '{{%category_fields}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');
        $this->addForeignKey('category_fields_field', '{{%category_fields}}', 'field_id', '{{%fields}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('category_fields_category', '{{%category_fields}}');
        $this->dropForeignKey('category_fields_field', '{{%category_fields}}');

        $this->dropTable('{{%category_fields}}');
    }
}
