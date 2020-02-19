<?php
namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m200219_130729_add_priority_to_same_products
 */
class m200219_130729_add_priority_to_same_products extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%product_same}}', 'priority', $this->integer()->defaultValue(0));
        $sql = "select * from product_same";
        $items = $this->db->createCommand($sql)->queryAll();
        foreach ($items as $item) {
            $id = $item['product_id'];
            $same_id = $item['product_same_id'];
            $sql = "select max(priority)+1 as result from product_same where product_id = '$id' AND product_same_id <> '$same_id'";
            $priority = $this->db->createCommand($sql)->queryOne()['result'];
            $priority = $priority !== null ? $priority : 1;
            $sql = "update product_same set priority = '$priority' where product_id = '$id' AND product_same_id = '$same_id'";
            $this->execute($sql);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%product_same}}', 'priority');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200219_130729_add_priority_to_same_products cannot be reverted.\n";

        return false;
    }
    */
}
