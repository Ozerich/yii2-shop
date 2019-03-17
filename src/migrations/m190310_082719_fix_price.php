<?php

use yii\db\Migration;

/**
 * Class m190310_082719_fix_price
 */
class m190310_082719_fix_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%products}}', 'price', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
