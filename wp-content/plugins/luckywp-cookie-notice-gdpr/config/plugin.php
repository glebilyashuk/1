<?php
return [
    'textDomain' => 'luckywp-cookie-notice-gdpr',
    'bootstrap' => [
        'activation',
        'admin',
    ],
    'pluginsLoadedBootstrap' => [
        'settings',
        'front',
    ],
    'components' => [
        'activation' => \luckywp\cookieNoticeGdpr\plugin\Activation::class,
        'admin' => \luckywp\cookieNoticeGdpr\admin\Admin::class,
        'front' => \luckywp\cookieNoticeGdpr\front\Front::class,
        'options' => \luckywp\cookieNoticeGdpr\core\wp\Options::class,
        'rate' => \luckywp\cookieNoticeGdpr\admin\Rate::class,
        'request' => \luckywp\cookieNoticeGdpr\core\base\Request::class,
        'settings' => [
            'class' => \luckywp\cookieNoticeGdpr\core\wp\Settings::class,
            'initGroupsConfigFile' => __DIR__ . '/settings.php',
        ],
        'view' => \luckywp\cookieNoticeGdpr\core\base\View::class,
    ],
];
