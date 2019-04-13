<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190413_000000_field_filters
 */
class m190413_000000_field_filters extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fields}}', 'filter_enabled', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%fields}}', 'filter_enabled');
    }
}
