<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190418_000000_manufactures
 */
class m190418_000000_manufactures extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $result = [];

        $parents = [];

        $products = $this->db->createCommand('SELECT id, manufacture_id FROM products WHERE manufacture_id IS NOT NULL')->queryAll();
        foreach ($products as $product) {
            if (!isset($result[$product['manufacture_id']])) {
                $result[$product['manufacture_id']] = [];
            }
            $categories = $this->db->createCommand('SELECT category_id from product_categories WHERE product_id=' . $product['id'])->queryColumn();
            foreach ($categories as $category_id) {
                if (!isset($parents[$category_id])) {
                    $parents[$category_id] = $this->db->createCommand('SELECT parent_id from categories where id = ' . $category_id)->queryScalar();
                }
                if (!in_array($category_id, $result[$product['manufacture_id']])) {
                    $result[$product['manufacture_id']][] = $category_id;
                }
                if ($parents[$category_id] && !in_array($parents[$category_id], $result[$product['manufacture_id']])) {
                    $result[$product['manufacture_id']][] = $parents[$category_id];
                }
            }
        }

        $this->createTable('{{%category_manufactures}}', [
            'category_id' => $this->integer()->notNull(),
            'manufacture_id' => $this->integer()->notNull()
        ]);

        $this->addPrimaryKey('category_manufactures_pk', '{{%category_manufactures}}', ['category_id', 'manufacture_id']);
        $this->addForeignKey('category_manufactures_category', '{{%category_manufactures}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');
        $this->addForeignKey('category_manufactures_manufacture', '{{%category_manufactures}}', 'manufacture_id', '{{%manufactures}}', 'id', 'CASCADE');

        foreach ($result as $manufacture_id => $category_ids) {
            foreach ($category_ids as $category_id) {
                $this->insert('{{%category_manufactures}}', [
                    'category_id' => $category_id,
                    'manufacture_id' => $manufacture_id
                ]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('category_manufactures_manufacture', '{{%category_manufactures}}');
        $this->dropForeignKey('category_manufactures_category', '{{%category_manufactures}}');
        $this->dropTable('{{%category_manufactures}}');
    }
}
