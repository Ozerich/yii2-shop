<?php

namespace ozerich\shop\migrations;


use yii\db\Migration;

/**
 * Class m190922_110859_blog
 */
class m190922_110859_blog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_posts_same}}', [
            'post_id' => $this->integer()->notNull(),
            'same_post_id' => $this->integer()->notNull()
        ]);

        $this->addPrimaryKey('blog_posts_same_pk', '{{%blog_posts_same}}', ['post_id', 'same_post_id']);
        $this->addForeignKey('blog_posts_same_post', '{{%blog_posts_same}}', 'post_id', '{{%blog_posts}}', 'id', 'CASCADE');
        $this->addForeignKey('blog_posts_same_same_post', '{{%blog_posts_same}}', 'same_post_id', '{{%blog_posts}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_posts_same}}');
    }
}
