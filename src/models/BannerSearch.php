<?php

namespace ozerich\shop\models;

use app\models\item\Item;
use ozerich\filestorage\models\File;
use yii\data\ActiveDataProvider;

class BannerSearch extends Banners
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }
    public function search($params, $sort = false)
    {
        $query = Banners::find();
        if($sort){
            $query->orderBy(['area_id' => SORT_ASC, 'priority' => SORT_ASC]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['area_id' => $this->area_id])
        ->andFilterWhere(['LIKE', 'title', $this->title]);
        return $dataProvider;
    }
}
