<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190323_004104_add_multiple
 */
class m190323_004104_add_multiple extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fields}}', 'multiple', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%fields}}', 'multiple');
    }
}
