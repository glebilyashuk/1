<?php

namespace luckywp\cookieNoticeGdpr\front\widgets\showAgain;

use luckywp\cookieNoticeGdpr\core\base\Widget;
use luckywp\cookieNoticeGdpr\core\Core;

class ShowAgain extends Widget
{

    public function run()
    {
        $label = Core::$plugin->settings->getValue('general', 'showAgainLabel');
        if (!$label) {
            $label = __('Privacy Policy', 'luckywp-cookie-notice-gdpr');
        }

        $colorScheme = Core::$plugin->settings->getValue('appearance', 'showAgainColorScheme');
        if (!in_array($colorScheme, ['light', 'dark'])) {
            $colorScheme = 'light';
        }

        $position = null;
        $position = Core::$plugin->settings->getValue('appearance', 'showAgainPosition');
        if (!in_array($position, ['bottomLeft', 'bottomRight'])) {
            $position = 'bottomRight';
        }

        return $this->render('widget', [
            'label' => $label,
            'show' => Core::$plugin->cookieAccepted || Core::$plugin->cookieRejected,
            'colorScheme' => $colorScheme,
            'position' => $position,
            'marginBottom' => (int)Core::$plugin->settings->getValue('appearance', 'showAgainMarginBottom'),
            'marginSide' => (int)Core::$plugin->settings->getValue('appearance', 'showAgainMarginSide'),
        ]);
    }
}
