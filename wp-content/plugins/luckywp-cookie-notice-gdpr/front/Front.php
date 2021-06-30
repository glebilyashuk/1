<?php

namespace luckywp\cookieNoticeGdpr\front;

use Exception;
use luckywp\cookieNoticeGdpr\core\Core;
use luckywp\cookieNoticeGdpr\core\front\BaseFront;
use luckywp\cookieNoticeGdpr\core\helpers\ArrayHelper;
use luckywp\cookieNoticeGdpr\front\widgets\showAgain\ShowAgain;
use luckywp\cookieNoticeGdpr\front\widgets\notice\Notice;

/**
 * @property-read bool $alwaysOutput
 */
class Front extends BaseFront
{

    protected $defaultThemeViewsDir = 'luckywp-cookie-notice-gdpr';

    public function init()
    {
        parent::init();
        if (Core::isFront()) {
            if ($this->getAlwaysOutput() ||
                (!Core::$plugin->cookieAccepted && !Core::$plugin->cookieRejected) ||
                Core::$plugin->useShowAgain
            ) {
                add_action('wp_footer', [$this, 'noticeHtml'], 9999);
                add_action('wp_enqueue_scripts', [$this, 'assets']);
            }

            if (Core::$plugin->cookieAccepted) {
                add_action('wp_head', function () {
                    echo Core::$plugin->settings->getValue('scripts', 'header');
                }, 9999);
                add_action('wp_body_open', function () {
                    echo Core::$plugin->settings->getValue('scripts', 'body');
                }, 9999);
                add_action('wp_footer', function () {
                    echo Core::$plugin->settings->getValue('scripts', 'footer');
                }, 9999);
            }
        }
    }

    public function noticeHtml()
    {
        echo Notice::widget();
        if ($this->getAlwaysOutput() || Core::$plugin->useShowAgain) {
            echo ShowAgain::widget();
        }
    }

    public function assets()
    {
        wp_register_style('lwpcng-main', Core::$plugin->url . '/front/assets/main.min.css', [], Core::$plugin->version);
        if (apply_filters('lwpcng_enqueue_style', true)) {
            wp_enqueue_style('lwpcng-main');
        }
        wp_register_script('lwpcng-main', Core::$plugin->url . '/front/assets/main.min.js', ['jquery'], Core::$plugin->version);
        if (apply_filters('lwpcng_enqueue_script', true)) {
            wp_enqueue_script('lwpcng-main');
        }
    }

    /**
     * @return bool
     */
    public function getAlwaysOutput()
    {
        $option = (string)Core::$plugin->settings->getValue('advanced', 'cachingPluginsIntegration');
        if (in_array($option, ['on', 'off'])) {
            return $option == 'on';
        }
        try {
            // WP Super Cache
            global $cache_enabled;
            if ($cache_enabled) {
                return true;
            }

            // W3 Total Cache
            if (function_exists('w3tc_config')) {
                return w3tc_config()->get_boolean('pgcache.enabled');
            }

            // WP Fastest Cache
            global $wp_fastest_cache_options;
            if ($wp_fastest_cache_options && is_object($wp_fastest_cache_options)) {
                return (bool)ArrayHelper::getValue((array)$wp_fastest_cache_options, 'wpFastestCacheStatus', false);
            }

            // LiteSpeed Cache
            if (class_exists('\LiteSpeed_Cache')) {
                return (bool)\LiteSpeed_Cache::config(\LiteSpeed_Cache_Const::OPID_ENABLED_RADIO);
            }

            //  WP Rocket
            if (defined('WP_ROCKET_VERSION')) {
                return true;
            }
        } catch (Exception $e) {
            return true;
        }
        return false;
    }
}
