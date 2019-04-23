<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190423_000001_blog
 */
class m190423_000001_blog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%blog_posts}}', 'status', $this->string()->notNull()->defaultValue('PUBLISHED'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%blog_posts}}', 'status');
    }
}
