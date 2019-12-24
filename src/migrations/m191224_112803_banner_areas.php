<?php

namespace ozerich\shop\migrations;
use yii\db\Migration;

/**
 * Class m191224_112803_banner_areas
 */
class m191224_112803_banner_areas extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%banner_areas}}', [
            'id' => $this->primaryKey(),
            'alias' => $this->string()->unique()->notNull(),
            'name' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%banner_areas}}');
    }
}
