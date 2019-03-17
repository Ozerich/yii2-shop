<?php

use yii\db\Migration;

/**
 * Class m190314_061530_pages
 */
class m190314_061530_pages extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pages}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'content' => 'LONGTEXT',
            'meta_title' => $this->string(),
            'meta_description' => $this->string(),
            'meta_image_id' => $this->integer()
        ]);

        $this->addForeignKey('page_meta_image', '{{%pages}}', 'meta_image_id', '{{%files}}', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('page_meta_image', '{{%pages}}');
        $this->dropTable('{{%pages}}');
    }
}
