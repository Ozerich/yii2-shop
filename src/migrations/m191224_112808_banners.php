<?php

namespace ozerich\shop\migrations;
use yii\db\Migration;

/**
 * Class m191224_112808_banners
 */
class m191224_112808_banners extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%banners}}', [
            'id' => $this->primaryKey(),
            'area_id' => $this->integer()->notNull(),
            'photo_id' => $this->integer()->notNull(),
            'url' => $this->string(),
            'title' => $this->string(),
            'text' => $this->text(),
            'priority' => $this->integer(),
        ]);
        $this->addForeignKey('banners_banner_area', '{{%banners}}', 'area_id', '{{%banner_areas}}', 'id');
        $this->addForeignKey('banners_photo_id', '{{%banners}}', 'photo_id', '{{%files}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('banners_photo_id', '{{%banners}}');
        $this->dropForeignKey('banners_banner_area', '{{%banners}}');
        $this->dropTable('{{%banners}}');
    }
}
