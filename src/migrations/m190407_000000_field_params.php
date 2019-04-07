<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190407_000000_field_params
 */
class m190407_000000_field_params extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fields}}', 'yes_label', $this->string());
        $this->addColumn('{{%fields}}', 'no_label', $this->string());

        $this->update('{{%fields}}', [
            'yes_label' => 'Да',
            'no_label' => 'Нет'
        ], 'type=:type', [':type' => 'BOOLEAN']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%fields}}', 'yes_label');
        $this->dropColumn('{{%fields}}', 'no_label');
    }
}
