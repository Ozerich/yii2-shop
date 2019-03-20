<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190320_230000_category_levels
 */
class m190321_153600_fix_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fields}}', 'category_id', $this->integer());
        $this->addForeignKey('field_category', '{{%fields}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');

        $this->addColumn('{{%field_groups}}', 'category_id', $this->integer());
        $this->addForeignKey('field_groups_category', '{{%field_groups}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');


        $fields = $this->db->createCommand('SELECT * FROM fields')->queryAll();
        $fields_map = [];
        foreach ($fields as $field) {
            $fields_map[$field['id']] = $field;
        }

        $category_fields = $this->db->createCommand('SELECT * FROM category_fields')->queryAll();

        $map = [];
        foreach ($category_fields as $category_field) {
            if (!isset($map[$category_field['category_id']])) {
                $map[$category_field['category_id']] = [];
            }
            $map[$category_field['category_id']][] = $category_field['field_id'];
        }

        $migrate_field_map = [];

        foreach ($map as $category_id => $field_ids) {
            foreach ($field_ids as $field_id) {
                if (!isset($migrate_field_map[$field_id])) {
                    $migrate_field_map[$field_id] = [];
                }

                if (!isset($migrate_field_map[$field_id][$category_id])) {
                    $migrate_field_map[$field_id][$category_id] = null;
                }

                $field = $fields_map[$field_id];

                $field_group_id = null;
                if ($field['group_id']) {
                    $found = $this->db->createCommand('SELECT * FROM field_groups WHERE id = ' . $field['group_id'])->queryOne();

                    $found_by_category = $this->db->createCommand('SELECT id FROM field_groups WHERE name=:name AND category_id=:category_id', [
                        ':name' => $found['name'],
                        ':category_id' => $category_id,
                    ])->queryOne();

                    if (!$found_by_category) {
                        unset($found['id']);
                        $found['category_id'] = $category_id;
                        $this->insert('{{%field_groups}}', $found);

                        $found_by_category = $this->db->createCommand('SELECT id FROM field_groups WHERE name=:name AND category_id=:category_id', [
                            ':name' => $found['name'],
                            ':category_id' => $category_id,
                        ])->queryOne();
                    }

                    $field_group_id = $found_by_category['id'];
                }

                $new_item = $field;
                unset($new_item['id']);
                $new_item['category_id'] = $category_id;
                $new_item['group_id'] = $field_group_id;

                $this->insert('{{%fields}}', $new_item);

                $field_new_id = $this->db->createCommand('SELECT id from fields WHERE category_id = :category_id AND name=:name ORDER by id desc LIMIT 0, 1', [
                    ':category_id' => $category_id,
                    ':name' => $new_item['name']
                ])->queryScalar();

                $migrate_field_map[$field_id][$category_id] = $field_new_id;
            }
        }

        $products_map = [];
        $products = $this->db->createCommand('SELECT id, category_id FROM products')->queryAll();
        foreach ($products as $product) {
            $products_map[$product['id']] = $product['category_id'];
        }

        $new_items = [];
        $values = $this->db->createCommand('SELECT * FROM product_field_values')->queryAll();
        foreach ($values as $value) {
            $category_id = $products_map[$value['product_id']];
            $new_field_id = $migrate_field_map[$value['field_id']][$category_id];
            if (!$new_field_id) {
                continue;
            }
            $new_items[] = [
                'product_id' => $value['product_id'],
                'field_id' => $new_field_id,
                'value' => $value['value']
            ];
        }

        $this->truncateTable('{{%product_field_values}}');
        foreach ($new_items as $item) {
            $this->insert('{{%product_field_values}}', $item);
        }


        $this->db->createCommand('DELETE FROM field_groups WHERE category_id is null');
        $this->db->createCommand('DELETE FROM fields WHERE category_id is null');

        $this->dropForeignKey('category_fields_category', '{{%category_fields}}');
        $this->dropForeignKey('category_fields_field', '{{%category_fields}}');

        $this->dropTable('{{%category_fields}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%category_fields}}', [
            'category_id' => $this->integer()->notNull(),
            'field_id' => $this->integer()->notNull()
        ]);

        $this->addPrimaryKey('category_fields_pk', '{{%category_fields}}', ['category_id', 'field_id']);

        $this->addForeignKey('category_fields_category', '{{%category_fields}}', 'category_id', '{{%categories}}', 'id', 'CASCADE');
        $this->addForeignKey('category_fields_field', '{{%category_fields}}', 'field_id', '{{%fields}}', 'id', 'CASCADE');

        $this->dropForeignKey('field_groups_category', '{{%field_groups}}');
        $this->dropColumn('{{%field_groups}}', 'category_id');

        $this->dropForeignKey('field_category', '{{%fields}}');
        $this->dropColumn('{{%fields}}', 'category_id');
    }
}
