<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190313_171052_filter_group
 */
class m190313_171052_filter_group extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%field_groups}}', [
            'id' => $this->primaryKey(),
            'name' => $this->integer(),
            'image_id' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('field_group_image', '{{%field_groups}}', 'image_id', '{{%files}}', 'id');

        $this->addColumn('{{%fields}}', 'group_id', $this->integer());
        $this->addForeignKey('field_group', '{{%fields}}', 'group_id', '{{%field_groups}}', 'id');

        $this->addColumn('{{%fields}}', 'image_id', $this->integer());
        $this->addForeignKey('field_image', '{{%fields}}', 'image_id', '{{%files}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('field_image', '{{%fields}}');
        $this->dropColumn('{{%fields}}', 'image_id');

        $this->dropForeignKey('field_group', '{{%fields}}');
        $this->dropColumn('{{%fields}}', 'group_id');

        $this->dropTable('{{%field_groups}}');
    }
}
