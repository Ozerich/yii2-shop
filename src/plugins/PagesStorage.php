<?php

namespace ozerich\shop\plugins;

class PagesStorage
{
    /** @var array[] */
    public static $pages = [];

    static function register(BasePlugin $plugin, $urlAlias, IPage $page)
    {
        if (!isset(self::$pages[$plugin->id()])) {
            self::$pages[$plugin->id()] = [];
        }

        self::$pages[$plugin->id()][$urlAlias] = $page;
    }

    static public function getMenuPages()
    {
        $result = [];

        foreach (self::$pages as $pluginId => $pages) {
            foreach ($pages as $url => $page) {
                if (!empty($page->menuLabel())) {
                    $result[] = [
                        'id' => $pluginId . '_' . (count($result) + 1),
                        'parent' => $page->menuParent(),
                        'label' => $page->menuLabel(),
                        'url' => '/plugin/page/' . $pluginId . '/' . $url
                    ];
                }
            }
        }

        return $result;
    }

    /**
     * @param $pluginId
     * @param $alias
     * @return IPage|null
     */
    static public function get($pluginId, $alias)
    {
        return isset(self::$pages[$pluginId][$alias]) ? self::$pages[$pluginId][$alias] : null;
    }
}