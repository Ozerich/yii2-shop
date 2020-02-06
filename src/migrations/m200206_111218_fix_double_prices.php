<?php
namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m200206_111218_fix_double_prices
 */
class m200206_111218_fix_double_prices extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('product_price_param', 'product_prices','product_id, param_value_id, param_value_second_id', 1);
        $prices = $products = [];
        $sql = "
            select * from product_prices
             where (product_id, param_value_id) in
                (SELECT product_id, param_value_id
                    FROM product_prices
                    where param_value_second_id is null
                    group by product_id, param_value_id
                    having count(*) > 1
                )  AND param_value_second_id is null
             ORDER BY id DESC, product_id, param_value_id
        ";
        $result = $this->db->createCommand($sql)->queryAll();

        foreach ($result as $item) {
            if(array_key_exists($item['product_id'], $prices)) {
                $products[] = $this->deletePrice($item);
            } else {
                $prices[$item['product_id']] = true;
            }
        }
        $this->updatePrices($products);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('product_price_param', 'product_prices');
    }

    private function deletePrice($item) {
        $id = $item['id'];
        $product_id = $item['product_id'];
        $result = $this->db->createCommand("DELETE FROM product_prices WHERE id = $id")->execute();
        return $product_id;
    }

    private function updatePrices($items) {
        foreach ($items as $item) {
            $resultPrice = $this->db->createCommand("
                select * from product_prices where `value` = (
                    select min(value) from product_prices where product_id = '$item' AND `value` IS NOT NULL 
                ) AND product_id = '$item'
            ")->queryOne();
            $resultStock = $this->db->createCommand("
                select MAX(case when stock = 'IN_SHOP' then 3 when stock = 'WAITING' then 1 when stock = 'STOCK' then 2 else 0 END) as weight
                 from product_prices where product_id = '$item'
            ")->queryOne();

            $bestStock = $this->getStockByWeigh($resultStock['weight']);
            $price = $resultPrice['value'];
            $disckount_mode = $resultPrice['discount_mode'];
            $discount_value = $resultPrice['discount_value'];
            $price_with_discount = $this->getPriceWithDiscount($price, $discount_value, $disckount_mode);

            $this->db->createCommand("
                UPDATE products SET 
                    price = '$price',
                    discount_mode = '$disckount_mode',
                    discount_value = '$discount_value',
                    price_with_discount = '$price_with_discount',
                    stock = '$bestStock'
                WHERE id = '$item'
            ")->execute();
        }
    }

    private function getStockByWeigh($weight) {
        switch ($weight) {
            case 3:
                return 'SHOP';
            case 2:
                return 'STOCK';
            case 1:
                return 'WAITING';
            default:
                return 'NO';
        }
    }

    private function getPriceWithDiscount($price, $discount_value, $discountType) {
        switch ($discountType) {
            case null:
                return $price;
            case 'PERCENT':
                return $price - floor($price / 100 * $discount_value);
            case 'FIXED':
                return $discount_value;
            case 'AMOUNT':
                return $price - $discount_value;
        }
    }
}
