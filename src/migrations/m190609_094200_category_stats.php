<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190609_094200_category_stats
 */
class m190609_094200_category_stats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'products_count', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%categories}}', 'min_price', $this->float());
        $this->addColumn('{{%categories}}', 'max_price', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%categories}}', 'products_count');
        $this->dropColumn('{{%categories}}', 'min_price');
        $this->dropColumn('{{%categories}}', 'max_price');
    }
}
