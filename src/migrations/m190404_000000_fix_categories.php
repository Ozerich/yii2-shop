<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190404_000000_fix_categories
 */
class m190404_000000_fix_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'category_id', $this->integer());

        $map = [];

        $items = $this->db->createCommand('SELECT * FROM product_categories')->queryAll();
        foreach ($items as $item) {
            if (!isset($map[$item['product_id']])) {
                $map[$item['product_id']] = $item['category_id'];
            }
        }

        foreach ($map as $product_id => $category_id) {
            $this->update('{{%products}}', ['category_id' => $category_id], 'id=:product_id', [':product_id' => $product_id]);
        }

        $this->addForeignKey('product_category', '{{%products}}', 'category_id', '{{%categories}}', 'id');

        $this->dropTable('{{%product_categories}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->db->createCommand('CREATE TABLE `product_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`),
  KEY `product_categories_category` (`category_id`),
  CONSTRAINT `product_categories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_categories_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;')->execute();

        $this->dropForeignKey('product_category', '{{%products}}');
        $this->dropColumn('{{%products}}', 'category_id');
    }
}
