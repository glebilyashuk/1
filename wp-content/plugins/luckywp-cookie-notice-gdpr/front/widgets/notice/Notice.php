<?php

namespace luckywp\cookieNoticeGdpr\front\widgets\notice;

use luckywp\cookieNoticeGdpr\core\base\Widget;
use luckywp\cookieNoticeGdpr\core\Core;
use luckywp\cookieNoticeGdpr\plugin\Plugin;

class Notice extends Widget
{

    public function run()
    {
        $moreLink = null;
        if (Core::$plugin->settings->getValue('general', 'showMore')) {
            switch (Core::$plugin->settings->getValue('general', 'moreLinkType')) {
                case 'page':
                    $moreLink = get_permalink((int)Core::$plugin->settings->getValue('general', 'morePageId'));
                    break;
                case 'custom':
                    $moreLink = Core::$plugin->settings->getValue('general', 'moreLink');
                    break;
            }
        }

        $colorScheme = Core::$plugin->settings->getValue('appearance', 'noticeColorScheme');
        if (!in_array($colorScheme, ['light', 'dark'])) {
            $colorScheme = 'dark';
        }

        $template = Core::$plugin->settings->getValue('appearance', 'noticeTemplate');
        if (!in_array($template, ['bar', 'box'])) {
            $template = 'bar';
        }

        $position = null;
        if ($template == 'bar') {
            $position = Core::$plugin->settings->getValue('appearance', 'noticeBarPosition');
            if (!in_array($position, ['top', 'bottom'])) {
                $position = 'bottom';
            }
        }
        if ($template == 'box') {
            $position = Core::$plugin->settings->getValue('appearance', 'noticeBoxPosition');
            if (!in_array($position, ['bottomLeft', 'bottomRight', 'topLeft', 'topRight'])) {
                $position = 'bottomRight';
            }
        }

        return $this->render('widget', [
            'message' => Core::$plugin->settings->getValue('general', 'message'),
            'buttonAcceptLabel' => Core::$plugin->settings->getValue('general', 'buttonAcceptLabel'),
            'buttonRejectLabel' => Core::$plugin->settings->getValue('general', 'showButtonReject') ? Core::$plugin->settings->getValue('general', 'buttonRejectLabel') : null,
            'moreLink' => $moreLink,
            'moreLabel' => $moreLink ? Core::$plugin->settings->getValue('general', 'moreLabel') : null,
            'moreLinkTarget' => $moreLink ? (Core::$plugin->settings->getValue('general', 'moreLinkTarget') == 'current' ? null : '_blank') : null,
            'show' => !Core::$plugin->cookieAccepted && !Core::$plugin->cookieRejected,
            'dataAttrs' => [
                'cookie-expire' => Core::$plugin->cookieExpire,
                'status-accepted' => Plugin::STATUS_ACCEPTED,
                'status-rejected' => Plugin::STATUS_REJECTED,
                'reload-after-accept' => (bool)Core::$plugin->settings->getValue('advanced', 'reloadAfterAccept'),
                'reload-after-reject' => (bool)Core::$plugin->settings->getValue('advanced', 'reloadAfterReject'),
                'use-show-again' => Core::$plugin->useShowAgain,
            ],
            'colorScheme' => $colorScheme,
            'template' => $template,
            'position' => $position,
            'margin' => (int)Core::$plugin->settings->getValue('appearance', 'noticeMargin'),
        ]);
    }
}
