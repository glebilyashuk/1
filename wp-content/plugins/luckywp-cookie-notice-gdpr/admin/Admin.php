<?php

namespace luckywp\cookieNoticeGdpr\admin;

use luckywp\cookieNoticeGdpr\admin\controllers\RateController;
use luckywp\cookieNoticeGdpr\admin\controllers\SettingsController;
use luckywp\cookieNoticeGdpr\core\admin\helpers\AdminUrl;
use luckywp\cookieNoticeGdpr\core\base\BaseObject;
use luckywp\cookieNoticeGdpr\core\Core;
use luckywp\cookieNoticeGdpr\core\helpers\Html;

class Admin extends BaseObject
{

    protected $pageSettingsHook;

    public function init()
    {
        if (is_admin()) {
            add_action('admin_menu', [$this, 'menu']);
            add_action('admin_enqueue_scripts', [$this, 'assets']);

            // Ссылки в списке плагинов
            add_filter('plugin_action_links_' . Core::$plugin->basename, function ($links) {
                array_unshift($links, Html::a(esc_html__('Settings', 'luckywp-cookie-notice-gdpr'), AdminUrl::toOptions('settings')));
                return $links;
            });

            // Контроллеры
            RateController::getInstance();
        }
    }

    public function menu()
    {
        $this->pageSettingsHook = add_submenu_page(
            'options-general.php',
            esc_html__('Cookie Notice (GDPR) Settings', 'luckywp-cookie-notice-gdpr'),
            esc_html__('Cookie Notice (GDPR)', 'luckywp-cookie-notice-gdpr'),
            'manage_options',
            Core::$plugin->prefix . 'settings',
            [SettingsController::class, 'router']
        );
    }

    public function assets($hook)
    {
        if ($hook == $this->pageSettingsHook) {
            wp_enqueue_style(Core::$plugin->prefix . 'adminMain', Core::$plugin->url . '/admin/assets/main.min.css', [], Core::$plugin->version);
            wp_enqueue_script(Core::$plugin->prefix . 'adminMain', Core::$plugin->url . '/admin/assets/main.min.js', ['jquery'], Core::$plugin->version);
        }
        if (Core::$plugin->rate->isShow()) {
            wp_enqueue_style(Core::$plugin->prefix . 'adminRate', Core::$plugin->url . '/admin/assets/rate.min.css', [], Core::$plugin->version);
            wp_enqueue_script(Core::$plugin->prefix . 'adminRate', Core::$plugin->url . '/admin/assets/rate.min.js', ['jquery'], Core::$plugin->version);
            wp_localize_script(Core::$plugin->prefix . 'adminRate', 'lwpcngRate', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
            ]);
        }
    }
}
