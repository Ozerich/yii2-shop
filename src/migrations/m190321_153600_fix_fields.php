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

        foreach ($map as $category_id => $field_ids) {
            foreach ($field_ids as $field_id) {
                $field = $fields_map[$field_id];

                $field_group_id = null;
                if ($field['group_id']) {
                    $found = $this->db->createCommand('SELECT * FROM field_groups WHERE id = ' . $field['group_id'])->queryOne();

                    $found_by_category = $this->db->createCommand('SELECT id FROM field_groups WHERE name=:name AND category_id=:category_id', [
                        ':name' => $field['name'],
                        ':category_id' => $field['category_id'],
                    ])->queryOne();

                    if (!$found_by_category) {
                        unset($found['id']);
                        $found['category_id'] = $category_id;
                        $this->insert('{{%field_groups}}', $found);
                    }

                    $found_by_category = $this->db->createCommand('SELECT id FROM field_groups WHERE name=:name AND category_id=:category_id', [
                        ':name' => $field['name'],
                        ':category_id' => $field['category_id'],
                    ])->queryOne();

                    $field_group_id = $found_by_category['id'];
                }

                $new_item = $field;
                unset($new_item['id']);
                $new_item['category_id'] = $category_id;
                $new_item['group_id'] = $field_group_id;

                $this->insert('{{%fields}}', $new_item);
            }
        }

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
