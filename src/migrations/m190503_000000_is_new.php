<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190503_000000_is_new
 */
class m190503_000000_is_new extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'is_new', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'is_new');
    }
}
