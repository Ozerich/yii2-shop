<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190304_204400_menu
 */
class m190304_204400_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->createTable('{{%menus}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()
        ]);

        $this->createTable('{{%menu_items}}', [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'priority' => $this->integer()
        ]);

        $this->addForeignKey('menu_items_menu', '{{%menu_items}}', 'menu_id', '{{%menus}}', 'id', 'CASCADE');
        $this->addForeignKey('menu_items_parent', '{{%menu_items}}', 'parent_id', '{{%menu_items}}', 'id', 'CASCADE');

        $this->insert('{{%menus}}', [
            'title' => 'Верхнее меню'
        ]);

        $this->insert('{{%menus}}', [
            'title' => 'Нижнее меню'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('menu_items_parent', '{{%menu_items}}');
        $this->dropForeignKey('menu_items_menu', '{{%menu_items}}');

        $this->dropTable('{{%menu_items}}');
        $this->dropTable('{{%menus}}');
    }
}
