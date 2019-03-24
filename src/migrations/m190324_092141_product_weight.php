<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190324_092141_product_weight
 */
class m190324_092141_product_weight extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%products}}', 'popular_weight', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'popular_weight');
    }
}
