<?php

namespace ozerich\shop\migrations;
use yii\db\Migration;

/**
 * Class m200403_115318_clear_prices
 */
class m200403_115318_clear_prices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "select product_id
            from product_prices group by product_id";

        $result = $this->db->createCommand($sql)->queryAll();

        foreach ($result as $item) {
            $sql = "select *
            from product_price_params where product_id = " . $item['product_id'];

            $params = $this->db->createCommand($sql)->queryAll();
            if(count($params) === 2) {
                $count1 = "select count(*) as counts from product_price_param_values where product_price_param_id = " . $params[0]['id'];
                $count2 = "select count(*) as counts from product_price_param_values where product_price_param_id = " . $params[1]['id'];

                $count3 = "select count(*) as counts from product_prices where product_id = " . $item['product_id'];

                $count1 = $this->db->createCommand($count1)->queryOne()['counts'];
                $count2 = $this->db->createCommand($count2)->queryOne()['counts'];
                $count3 = $this->db->createCommand($count3)->queryOne()['counts'];
                if($count3 > $count1 * $count2) {
                    $ids = "SELECT id from product_prices where product_id = " . $item['product_id'] . " order by id limit " . (int)($count3 - $count1 * $count2) ;
                    $ids = $this->db->createCommand($ids)->queryAll();
                    $ids = array_map(function ($i) {
                        return $i['id'];
                    }, $ids);
                    $sql = "DELETE FROM product_prices where id in (" . implode(',', $ids) . ")";
                    $this->db->createCommand($sql)->execute();
                }
            } elseif(count($params) === 1) {
                $count1 = "select count(*) as counts from product_price_param_values where product_price_param_id = " . $params[0]['id'];
                $count2 = "select count(*) as counts from product_prices where product_id = " . $item['product_id'];

                if($this->db->createCommand($count2)->queryOne()['counts'] > $this->db->createCommand($count1)->queryOne()['counts']) {
                    echo $item['product_id'];
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200403_115318_clear_prices cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200403_115318_clear_prices cannot be reverted.\n";

        return false;
    }
    */
}
