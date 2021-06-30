<?php

namespace luckywp\cookieNoticeGdpr\core\admin\helpers;

use luckywp\cookieNoticeGdpr\core\Core;

class AdminUrl
{

    /**
     * @param string $pageId
     * @param string|null $action
     * @param array $params
     * @return string
     */
    public static function to($pageId, $action = null, $params = [])
    {
        return static::makeUrl('admin.php', $pageId, $action, $params);
    }

    /**
     * @param string $pageId
     * @param string|null $action
     * @param array $params
     * @return string
     */
    public static function toOptions($pageId, $action = null, $params = [])
    {
        return static::makeUrl('options-general.php', $pageId, $action, $params);
    }

    /**
     * @param string $wpPage
     * @param string $pageId
     * @param string|null $action
     * @param array $params
     * @return string
     */
    protected static function makeUrl($wpPage, $pageId, $action = null, $params = [])
    {
        $params['page'] = Core::$plugin->prefix . $pageId;
        if ($action !== null) {
            $params['action'] = $action;
        }
        return admin_url($wpPage . '?' . http_build_query($params));
    }

    /**
     * @param string $pageId
     * @param string $action
     * @return bool
     */
    public static function isPage($pageId, $action = '')
    {
        return Core::$plugin->request->get('page') == Core::$plugin->prefix . $pageId
            && (!$action || Core::$plugin->request->get('action') == $action);
    }
}
