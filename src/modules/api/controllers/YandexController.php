<?php

namespace ozerich\shop\modules\api\controllers;

use ozerich\shop\constants\CategoryType;
use ozerich\shop\models\Category;
use ozerich\shop\models\Product;
use ozerich\shop\utils\yml\YmlDocument;
use yii\web\Response;

class YandexController extends \yii\web\Controller
{
    public function actionCatalog()
    {
        ini_set('memory_limit', '1024M');

        $filename = \Yii::getAlias('@runtime').'/tmp.yml';

        $document = new YmlDocument('БелМебель', 'ЧУП "ОзиС"');
        $document->fileName($filename)->bufferSize(1024 * 1024 * 16);
        $document->url('https://belmebel.by');
        $document->currency('BYN', 1);


        /** @var Category[] $categories */
        $categories = Category::find()->andWhere('type=:type', [':type' => CategoryType::CATALOG])->all();
        foreach ($categories as $category) {
            $document->category($category->id, $category->name, $category->parent_id ? $category->parent_id : false);
        }

        /** @var Product[] $products */
        $products = Product::find()->andWhere('price > 0')->joinWith('images')->joinWith('productFieldValues')->all();
        foreach ($products as $product) {
            $offer = $document->simple($product->name, $product->id, $product->price, 'BYN', $product->category_id ? $product->category_id : false);
            $offer->url($product->getUrl(true));

            $offer->delivery(true);


            $offer->description(mb_substr($product->text, 0, 3000));

            $offer->warranty();
            $offer->cpa(false);

            $offer->picture($product->image ? $product->image->getUrl() : null);
            $c = 0;
            foreach ($product->images as $ind => $image) {
                if ($c++ > 9) {
                    break;
                }
                $offer->pic($image->getUrl());
            }

            foreach ($product->productFieldValues as $productFieldValue) {
                $offer->param($productFieldValue->field->name, $productFieldValue->value);
            }
        }
        unset($document);

        $f = fopen($filename, 'r+');
        $response = fread($f, filesize($filename));
        fclose($f);

        \Yii::$app->response->format = Response::FORMAT_XML;
        \Yii::$app->response->content = $response;
    }
}