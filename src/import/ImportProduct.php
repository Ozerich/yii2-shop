<?php

namespace ozerich\shop\import;

class ImportProduct
{
    private $id;

    private $sku;

    private $name;

    private $url;

    private $params = [];

    private $video;

    private $schema;

    private $mainImage;

    private $images = [];

    private $priceParams = [];

    private $price;

    private $oldPrice;

    private $priceForParams = [];

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSku($value)
    {
        $this->sku = $value;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setParam($param, $value)
    {
        $this->params[$param] = $value;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setVideo($value)
    {
        $this->video = $value;
    }

    public function getVideo()
    {
        return $this->video;
    }

    public function setSchema($value)
    {
        $this->schema = $value;
    }

    public function getSchema()
    {
        return $this->schema;
    }

    public function addImage($url, $description = false, $isMain = false)
    {
        $this->images[] = [
            'url' => $url,
            'description' => $description
        ];

        if ($isMain) {
            $this->mainImage = $url;
        }
    }

    public function getMainImageUrl()
    {
        return $this->mainImage;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function addPriceParam($label, $options)
    {
        $this->priceParams[] = [
            'label' => $label,
            'options' => $options
        ];
    }

    public function getPriceParams()
    {
        return $this->priceParams;
    }


    public function setOldPrice($value)
    {
        $this->oldPrice = $value;
    }

    public function getOldPrice()
    {
        return $this->oldPrice;
    }

    public function setPrice($value)
    {
        $this->price = $value;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPriceForParam($params, $price, $oldPrice = null)
    {
        $this->priceForParams[] = [
            'params' => $params,
            'price' => $price,
            'oldPrice' => $oldPrice
        ];
    }

    public function getExtendedPrices()
    {
        return $this->priceForParams;
    }
}