<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190312_195358_category_text
 */
class m190312_195358_category_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'text', 'LONGTEXT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%categories}}', 'text');
    }
}
