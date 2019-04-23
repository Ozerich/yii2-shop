<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190423_000001_blog
 */
class m190424_000000_prices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'price_note', $this->text());
        $this->addColumn('{{%products}}', 'is_price_from', $this->boolean()->notNull()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'price_note');
        $this->dropColumn('{{%products}}', 'is_price_from');
    }
}
