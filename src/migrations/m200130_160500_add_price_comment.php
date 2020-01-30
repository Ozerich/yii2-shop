<?php
namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m200130_160500_add_price_comment
 */
class m200130_160500_add_price_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_prices}}', 'comment', $this->text());
        $this->addColumn('{{%products}}', 'price_comment', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_prices}}', 'comment');
        $this->dropColumn('{{%products}}', 'price_comment');
    }
}
