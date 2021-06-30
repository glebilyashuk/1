<?php

namespace luckywp\cookieNoticeGdpr\plugin;

use luckywp\cookieNoticeGdpr\admin\Admin;
use luckywp\cookieNoticeGdpr\admin\Rate;
use luckywp\cookieNoticeGdpr\core\base\BasePlugin;
use luckywp\cookieNoticeGdpr\core\base\Request;
use luckywp\cookieNoticeGdpr\core\base\View;
use luckywp\cookieNoticeGdpr\core\Core;
use luckywp\cookieNoticeGdpr\core\helpers\ArrayHelper;
use luckywp\cookieNoticeGdpr\core\wp\Options;
use luckywp\cookieNoticeGdpr\core\wp\Settings;
use luckywp\cookieNoticeGdpr\front\Front;

/**
 * @property-read Admin $admin
 * @property-read Front $front
 * @property-read Options $options
 * @property-read Rate $rate
 * @property-read Request $request
 * @property-read Settings $settings
 * @property-read View $view
 *
 * @property-read int $cookieExpire
 * @property-read bool $cookieAccepted
 * @property-read bool $cookieRejected
 * @property-read bool $useShowAgain
 */
class Plugin extends BasePlugin
{

    /**
     * Время жизни куки (по-умолчанию)
     */
    const DEFAULT_COOKIE_EXPIRE = 365; // дней

    /**
     * Имя куки
     */
    const COOKIE_NAME = 'lwpcngStatus';

    /**
     * Статус: Принято
     */
    const STATUS_ACCEPTED = 1;

    /**
     * Статус: Отклонено
     */
    const STATUS_REJECTED = 2;

    /**
     * Время жизни кук в миллисекундах
     * @return int
     */
    public function getCookieExpire()
    {
        $cookieExpire = (int)Core::$plugin->settings->getValue('advanced', 'cookieExpire');
        if ($cookieExpire < 1) {
            $cookieExpire = Plugin::DEFAULT_COOKIE_EXPIRE;
        }
        return $cookieExpire * 24 * 60 * 60 * 1000;
    }

    public function getCookieAccepted()
    {
        return ArrayHelper::getValue($_COOKIE, Plugin::COOKIE_NAME) == Plugin::STATUS_ACCEPTED;
    }

    public function getCookieRejected()
    {
        return ArrayHelper::getValue($_COOKIE, Plugin::COOKIE_NAME) == Plugin::STATUS_REJECTED;
    }

    public function getUseShowAgain()
    {
        return (bool)$this->settings->getValue('general', 'useShowAgain');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'LuckyWP Cookie Notice (GDPR)';
    }

    private function pluginI18n()
    {
        _('The plugin allows you to notify visitors about the use of cookies (necessary to comply with the GDPR in the EU).');
    }
}
