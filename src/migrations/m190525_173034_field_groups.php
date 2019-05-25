<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190525_173034_field_groups
 */
class m190525_173034_field_groups extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('field_group_image', '{{%field_groups}}');

        $this->dropForeignKey('field_group', '{{%fields}}');
        $this->dropColumn('{{%fields}}', 'group_id');

        $this->dropTable('{{%field_groups}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%field_groups}}', [
            'id' => $this->primaryKey(),
            'name' => $this->integer(),
            'image_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('field_group_image', '{{%field_groups}}', 'image_id', '{{%files}}', 'id');

        $this->addColumn('{{%fields}}', 'group_id', $this->integer());
        $this->addForeignKey('field_group', '{{%fields}}', 'group_id', '{{%field_groups}}', 'id');
    }
}
