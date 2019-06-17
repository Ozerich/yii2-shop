<?php

namespace ozerich\shop\plugins;

interface IPlugin
{
    public function id();

    public function bootstrap();
}