<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190422_000000_settings
 */
class m190422_000000_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'option' => $this->string()->notNull(),
            'value' => $this->text()
        ]);

        $this->createIndex('settings_option', '{{%settings}}', 'option', true);

        $this->addColumn('{{%categories}}', 'home_display', $this->boolean()->notNull()->defaultValue(false));
        $this->addColumn('{{%categories}}', 'home_position', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%categories}}', 'home_display');
        $this->dropColumn('{{%categories}}', 'home_position');

        $this->dropTable('{{%settings}}');
    }
}
