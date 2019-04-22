<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190423_000000_blog
 */
class m190423_000000_blog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%blog_categories}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'url_alias' => $this->string(),
            'name' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'image_id' => $this->integer(),
            'page_title' => $this->string()->notNull(),
            'meta_description' => $this->text()
        ]);

        $this->addForeignKey('blog_category_parent', '{{%blog_categories}}', 'parent_id', '{{%blog_categories}}', 'id', 'SET NULL');
        $this->addForeignKey('blog_category_image', '{{%blog_categories}}', 'image_id', '{{%files}}', 'id', 'CASCADE');

        $this->createTable('{{%blog_posts}}', [
            'id' => $this->primaryKey(),
            'url_alias' => $this->string()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'image_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'excerpt' => $this->text(),
            'content' => 'LONGTEXT',
            'page_title' => $this->string(),
            'meta_description' => $this->text()
        ]);

        $this->addForeignKey('blog_posts_category', '{{%blog_posts}}', 'category_id', '{{%blog_categories}}', 'id');
        $this->addForeignKey('blog_posts_image', '{{%blog_posts}}', 'image_id', '{{%files}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropForeignKey('blog_posts_image', '{{%blog_posts}}');
       $this->dropForeignKey('blog_posts_category', '{{%blog_posts}}');
       $this->dropTable('{{%blog_posts}}');

       $this->dropForeignKey('blog_category_image', '{{%blog_categories}}');
       $this->dropForeignKey('blog_category_parent', '{{%blog_categories}}');
       $this->dropTable('{{%blog_categories}}');
    }
}
