<?php

namespace ozerich\shop\migrations;


use yii\db\Migration;

/**
 * Class m190922_110759_blog
 */
class m190922_110759_blog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%blog_posts}}', 'priority', $this->integer()->notNull());

        $this->createTable('{{%blog_posts_to_product_categories}}', [
            'post_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('blog_pk', '{{%blog_posts_to_product_categories}}', ['post_id', 'category_id']);
        $this->addForeignKey('blog_posts_to_product_categories_post', '{{blog_posts_to_product_categories}}', 'post_id', '{{%blog_posts}}', 'id', 'CASCADE');
        $this->addForeignKey('blog_posts_to_product_categories_category', '{{blog_posts_to_product_categories}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');

        $items = $this->db->createCommand('SELECT * FROM blog_posts')->queryAll();
        foreach ($items as $ind => $item) {
            $this->update('{{%blog_posts}}', ['priority' => $ind + 1], 'id=:id', [':id' => $item['id']]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%blog_posts_to_product_categories}}');
        $this->dropColumn('{{%blog_posts}}', 'priority');
    }
}
