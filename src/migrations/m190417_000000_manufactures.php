<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190417_000000_manufactures
 */
class m190417_000000_manufactures extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%manufactures}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'url_alias' => $this->string()->notNull(),
            'image_id' => $this->integer(),
            'content' => 'LONGTEXT',
            'seo_title' => $this->string(1000),
            'seo_description' => $this->text(),
        ]);

        $this->addForeignKey('manufacture_image', '{{%manufactures}}', 'image_id', '{{%files}}', 'id', 'CASCADE');

        $this->addColumn('{{%products}}', 'manufacture_id', $this->integer());
        $this->addForeignKey('product_manufacture', '{{%products}}', 'manufacture_id', '{{%manufactures}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('product_manufacture', '{{%products}}');
        $this->dropColumn('{{%products}}', 'manufacture_id');
        $this->dropForeignKey('manufacture_image', '{{%manufactures}}');
        $this->dropTable('{{%manufactures}}');
    }
}
