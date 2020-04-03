<?php

namespace ozerich\shop\migrations;
use yii\db\Migration;

/**
 * Class m200403_092238_add_zero_prices
 */
class m200403_092238_add_zero_prices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "
            select product_price_params.*, product_price_param_values.id as param_id
            from product_price_params
            LEFT JOIN product_price_param_values ON product_price_param_values.product_price_param_id = product_price_params.id";

        $result = $this->db->createCommand($sql)->queryAll();

        $array = [];
        foreach ($result as $item) {
            $sql = "Select * from product_price_params where product_id = " . $item['product_id'];
            $params = $this->db->createCommand($sql)->queryAll();
            if(count($params) === 1) {
                $sql = "
                    INSERT INTO product_prices (product_id, param_value_id) 
                    SELECT " . $item['product_id'] . ", pppv.id
                    FROM product_price_param_values pppv
                    LEFT JOIN product_price_params on product_price_params.id = pppv.product_price_param_id
                    WHERE pppv.id not in (
                            Select param_value_id from product_prices where param_value_id = pppv.id and product_id = " . $item['product_id'] . " )
                    and product_id = " . $item['product_id'] . "
			    ";
                $this->db->createCommand($sql)->execute();
            } elseif(count($params) === 2) {
                $param1 = $params[0];
                $param2 = $params[1];

                $sql = "select * from product_price_param_values where product_price_param_id = " . $param1['id'];
                $values = $this->db->createCommand($sql)->queryAll();
                foreach ($values as $value) {
                    $sql = "
                    SELECT " . $item['product_id'] . " as product_id, pppv.id, " . $value['id'] . " as param_id
                    FROM product_price_param_values pppv
                    LEFT JOIN product_price_params on product_price_params.id = pppv.product_price_param_id
                    WHERE pppv.id not in (
                            Select param_value_id from product_prices
                               where 
                                  param_value_id = pppv.id AND param_value_second_id = " . $value['id'] . " 
                                and product_id = " . $item['product_id'] . "
                         ) and  pppv.id not in (
                            Select param_value_second_id from product_prices
                               where param_value_id = " . $value['id'] . " AND param_value_second_id = pppv.id
                                and product_id = " . $item['product_id'] . "
                         )
                    and product_id = " . $item['product_id'] . " and pppv.id <> " . $value['id'] . " and product_price_params.id <> " . $param1['id'] . "
                ";
                    $res = $this->db->createCommand($sql)->queryAll();
                    foreach ($res as $r) {
                        $tmp = array_filter($array, function ($i) use ($r) {
                           if($i['product_id'] === $r['product_id'] && $i['id'] === $r['id']  && $i['param_id'] === $r['param_id'] ) {
                               return true;
                           }
                           if($i['product_id'] === $r['product_id'] && $i['param_id'] === $r['id']  && $i['id'] === $r['param_id'] ) {
                               return true;
                           }
                           return false;
                        });
                        if(!$tmp) {
                            $array[] = $r;
                        }
                    }
                }
            }
        }
        foreach ($array as $item) {
            $this->insert('product_prices', [
                'product_id' => $item['product_id'],
                'param_value_id' => $item['id'],
                'param_value_second_id' => $item['param_id'],
            ]);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200403_092238_add_zero_prices cannot be reverted.\n";

        return false;
    }
    */
}
