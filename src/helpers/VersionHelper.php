<?php


namespace ozerich\shop\helpers;


class VersionHelper
{
    public static function getModuleVersion($name = 'ozerich/yii2-shop'){
        $composer = \Yii::getAlias('@app') . "/composer.lock";
        $json = json_decode(file_get_contents($composer), true);
        if(is_array($json) && array_key_exists('packages', $json)){
            $package = array_filter($json['packages'], function ($e) use ($name) {
                return $e['name'] == $name;
            });
            $package = array_shift($package);
            if(is_array($package)){
                $version = array_key_exists('version', $package) ? $package['version'] : null;
                $time = array_key_exists('time', $package) ? $package['time'] : null;
                $time = $time ? date('d.m.Y H:i', strtotime($time)) : null;
                return $version . ' ' . $time;
            }
        }
    }
}
