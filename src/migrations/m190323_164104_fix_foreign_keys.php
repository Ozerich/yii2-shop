<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190323_154104_fix_foreign_keys
 */
class m190323_164104_fix_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'schema_image_id', $this->integer());
        $this->addForeignKey('product_schema', '{{%products}}', 'schema_image_id', '{{%files}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_schema', '{{%products}}');
        $this->dropColumn('{{%products}}', 'schema_image_id');
    }
}
