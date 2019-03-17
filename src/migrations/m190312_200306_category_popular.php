<?php

use yii\db\Migration;

/**
 * Class m190312_200306_category_popular
 */
class m190312_200306_category_popular extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'popular', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'popular');
    }
}
