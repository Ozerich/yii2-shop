<?php

namespace ozerich\shop\modules\admin\filters;

use ozerich\shop\models\BlogPost;
use yii\db\ActiveQuery;

class FilterBlogPosts extends BlogPost
{
    public function rules()
    {
        return [
            [['status'], 'safe']
        ];
    }

    public function search(ActiveQuery $query)
    {
        if ($this->status) {
            $query->andWhere('status=:status', [':status' => $this->status]);
        }

        return $query;
    }
}