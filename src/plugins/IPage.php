<?php

namespace ozerich\shop\plugins;

interface IPage
{
    public function pageTitle();

    public function menuLabel();

    public function menuParent();

    public function render();
}