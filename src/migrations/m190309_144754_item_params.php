<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190309_144754_item_params
 */
class m190309_144754_item_params extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'price', $this->integer()->notNull());
        $this->addColumn('{{%products}}', 'text', 'LONGTEXT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'text');
        $this->dropColumn('{{%products}}', 'price');
    }
}
