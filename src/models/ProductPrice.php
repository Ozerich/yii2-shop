<?php

namespace ozerich\shop\models;

/**
 * This is the model class for table "{{%product_prices}}".
 *
 * @property int $id
 * @property int $product_id
 * @property int $param_value_id
 * @property int $param_value_second_id
 * @property float $value
 * @property string $discount_mode
 * @property integer $discount_value
 * @property string $stock
 * @property integer $stock_waiting_days
 *
 * @property ProductPriceParamValue $paramValue
 * @property Product $product
 * @property Currency $currency_id
 */
class ProductPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_prices}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParamValue()
    {
        return $this->hasOne(ProductPriceParamValue::className(), ['id' => 'param_value_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParamSecondValue()
    {
        return $this->hasOne(ProductPriceParamValue::className(), ['id' => 'param_value_second_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public static function findByParamIds($first_param_value_id, $second_param_value_id)
    {
        $query = self::find();

        if ($first_param_value_id) {
            $query->andWhere('param_value_id=:param_value_id', [':param_value_id' => $first_param_value_id]);
        }

        if ($second_param_value_id) {
            $query->andWhere('param_value_second_id=:param_value_second_id', [':param_value_second_id' => $second_param_value_id]);
        }

        return $query;
    }
}
