<?php

use luckywp\cookieNoticeGdpr\admin\controllers\SettingsController;
use luckywp\cookieNoticeGdpr\core\admin\helpers\AdminHtml;
use luckywp\cookieNoticeGdpr\plugin\Plugin;

return [

    // Основные настройки
    'general' => [
        'label' => esc_html__('General', 'luckywp-cookie-notice-gdpr'),
        'sections' => [
            'notice' => [
                'title' => esc_html__('Notice', 'luckywp-cookie-notice-gdpr'),
                'fields' => [

                    'message' => [
                        'label' => esc_html__('Message', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textarea',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_LARGE,
                            ],
                        ],
                        'default' => esc_html__('We use cookies in order to give you the best possible experience on our website. By continuing to use this site, you agree to our use of cookies.', 'luckywp-cookie-notice-gdpr'),
                    ],
                ],
            ],

            'buttons' => [
                'title' => esc_html__('Buttons', 'luckywp-cookie-notice-gdpr'),
                'fields' => [

                    'buttonAcceptLabel' => [
                        'label' => esc_html__('Button "Accept" Label', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textInput',
                        'default' => esc_html__('Accept', 'luckywp-cookie-notice-gdpr'),
                    ],

                    'showButtonReject' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Show button "Reject"', 'luckywp-cookie-notice-gdpr'),
                                'class' => 'js-lwpcngGeneralShowButtonReject'
                            ],
                        ],
                        'default' => false,
                    ],

                    'buttonRejectLabel' => [
                        'label' => esc_html__('Button "Reject" Label', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'class' => 'js-lwpcngGeneralButtonRejectLabel',
                            ],
                        ],
                        'default' => esc_html__('Reject', 'luckywp-cookie-notice-gdpr'),
                    ],

                    'showMore' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Show button "More"', 'luckywp-cookie-notice-gdpr'),
                                'class' => 'js-lwpcngShowMore'
                            ],
                        ],
                        'default' => false,
                    ],
                ],
            ],

            'more' => [
                'title' => esc_html__('Button "More"', 'luckywp-cookie-notice-gdpr'),
                'fields' => [

                    'moreLabel' => [
                        'label' => esc_html__('Button Label', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textInput',
                        'default' => esc_html__('Read More…', 'luckywp-cookie-notice-gdpr'),
                    ],

                    'moreLinkType' => [
                        'label' => esc_html__('Link Type', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'page' => esc_html__('Page link', 'luckywp-cookie-notice-gdpr'),
                                'custom' => esc_html__('Custom link', 'luckywp-cookie-notice-gdpr'),
                            ],
                            'selectOptions' => [
                                'class' => 'js-lwpcngMoreLinkType',
                            ],
                        ],
                        'default' => 'page',
                    ],

                    'morePageId' => [
                        'label' => esc_html__('Page', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'pages',
                        'params' => [
                            'selectOptions' => [
                                'class' => 'js-lwpcngMorePageId',
                            ],
                        ],
                    ],

                    'moreLink' => [
                        'label' => esc_html__('Link', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'class' => 'js-lwpcngMoreLink',
                            ],
                        ],
                    ],

                    'moreLinkTarget' => [
                        'label' => esc_html__('Open Link in…', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                '' => esc_html__('New tab', 'luckywp-cookie-notice-gdpr'),
                                'current' => esc_html__('Current tab', 'luckywp-cookie-notice-gdpr'),
                            ],
                        ],
                    ],
                ],
            ],

            'showAgain' => [
                'title' => esc_html__('Button "Show Again"', 'luckywp-cookie-notice-gdpr'),
                'fields' => [

                    'useShowAgain' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('Use button "Show Again"', 'luckywp-cookie-notice-gdpr'),
                                'class' => 'js-lwpcngUseShowAgain'
                            ],
                        ],
                        'default' => false,
                    ],

                    'showAgainLabel' => [
                        'label' => esc_html__('Button Label', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'class' => 'js-lwpcngShowAgainLabel',
                            ],
                        ],
                        'default' => esc_html__('Privacy Policy', 'luckywp-cookie-notice-gdpr'),
                    ],
                ],
            ],
        ],
    ],

    // Внешний вид
    'appearance' => [
        'label' => esc_html__('Appearance', 'luckywp-cookie-notice-gdpr'),
        'sections' => [
            'noticeAppearance' => [
                'title' => esc_html__('Notice', 'luckywp-cookie-notice-gdpr'),
                'fields' => [

                    'noticeColorScheme' => [
                        'label' => esc_html__('Color Scheme', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'light' => esc_html__('Light', 'luckywp-cookie-notice-gdpr'),
                                'dark' => esc_html__('Dark', 'luckywp-cookie-notice-gdpr'),
                            ],
                        ],
                        'default' => 'dark',
                    ],

                    'noticeTemplate' => [
                        'label' => esc_html__('Template', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'bar' => esc_html__('Bar', 'luckywp-cookie-notice-gdpr'),
                                'box' => esc_html__('Box', 'luckywp-cookie-notice-gdpr'),
                            ],
                            'selectOptions' => [
                                'class' => 'js-lwpcngTemplate',
                            ],
                        ],
                        'default' => 'bar',
                    ],

                    'noticeBarPosition' => [
                        'label' => esc_html__('Bar Position', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'top' => esc_html__('Top', 'luckywp-cookie-notice-gdpr'),
                                'bottom' => esc_html__('Bottom', 'luckywp-cookie-notice-gdpr'),
                            ],
                            'selectOptions' => [
                                'class' => 'js-lwpcngBarPosition',
                            ],
                        ],
                        'default' => 'bottom',
                    ],

                    'noticeBoxPosition' => [
                        'label' => esc_html__('Box Position', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'bottomLeft' => esc_html__('Bottom Left', 'luckywp-cookie-notice-gdpr'),
                                'bottomRight' => esc_html__('Bottom Right', 'luckywp-cookie-notice-gdpr'),
                                'topLeft' => esc_html__('Top Left', 'luckywp-cookie-notice-gdpr'),
                                'topRight' => esc_html__('Top Right', 'luckywp-cookie-notice-gdpr'),
                            ],
                            'selectOptions' => [
                                'class' => 'js-lwpcngBoxPosition',
                            ],
                        ],
                        'default' => 'bottomRight',
                    ],

                    'noticeMargin' => [
                        'label' => esc_html__('Margin', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_SMALL,
                            ],
                            'after' => ' px',
                        ],
                        'default' => 0,
                    ],
                ],
            ],
            'showAgainAppearance' => [
                'title' => esc_html__('Button "Show Again"', 'luckywp-cookie-notice-gdpr'),
                'fields' => [

                    'showAgainColorScheme' => [
                        'label' => esc_html__('Color Scheme', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'light' => esc_html__('Light', 'luckywp-cookie-notice-gdpr'),
                                'dark' => esc_html__('Dark', 'luckywp-cookie-notice-gdpr'),
                            ],
                        ],
                        'default' => 'light',
                    ],

                    'showAgainPosition' => [
                        'label' => esc_html__('Position', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'bottomLeft' => esc_html__('Bottom Left', 'luckywp-cookie-notice-gdpr'),
                                'bottomRight' => esc_html__('Bottom Right', 'luckywp-cookie-notice-gdpr'),
                            ],
                        ],
                        'default' => 'bottomRight',
                    ],

                    'showAgainMarginBottom' => [
                        'label' => esc_html__('Margin Bottom', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_SMALL,
                            ],
                            'after' => ' px',
                        ],
                        'default' => 0,
                    ],

                    'showAgainMarginSide' => [
                        'label' => esc_html__('Margin Side', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_SMALL,
                            ],
                            'after' => ' px',
                        ],
                        'default' => 24,
                    ],
                ],
            ],
        ],
    ],

    // Скрипты
    'scripts' => [
        'label' => esc_html__('Scripts', 'luckywp-cookie-notice-gdpr'),
        'sections' => [
            'scripts' => [
                'desc' => '<br><p>' .
                    esc_html__('This scripts will be added to the page if user has give consent.', 'luckywp-cookie-notice-gdpr') .
                    '<br>' .
                    sprintf(
                        esc_html__('To get the cookie notice status in PHP use %s or %s functions.', 'luckywp-cookie-notice-gdpr'),
                        '<code>lwpcng_cookies_accepted()</code>',
                        '<code>lwpcng_cookies_rejected()</code>'
                    ) .
                    '</p>',
                'fields' => [

                    'header' => [
                        'label' => esc_html__('Header', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textarea',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_LARGE,
                            ],
                        ],
                        'desc' => esc_html__('Before the closing HEAD tag. Example,', 'luckywp-cookie-notice-gdpr') . ' <code>&lt;script&gt;console.log("The script in the header");&lt;/script&gt;</code>',
                    ],

                    'body' => [
                        'label' => sprintf(
                        /* translators: %s: <body> */
                            esc_html__('After opening %s', 'luckywp-cookie-notice-gdpr'),
                            '&lt;body&gt;'
                        ),
                        'widget' => 'textarea',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_LARGE,
                            ],
                        ],
                        'desc' => esc_html__('After the opening BODY tag. Example,', 'luckywp-cookie-notice-gdpr') . ' <code>&lt;script&gt;console.log("The script after opening BODY tag");&lt;/script&gt;</code>',
                    ],

                    'footer' => [
                        'label' => esc_html__('Footer', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textarea',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_LARGE,
                            ],
                        ],
                        'desc' => esc_html__('Before the closing BODY tag. Example,', 'luckywp-cookie-notice-gdpr') . ' <code>&lt;script&gt;console.log("The script in the footer");&lt;/script&gt;</code>',
                    ],
                ],
            ],
        ],
    ],

    // Дополнительно
    'advanced' => [
        'label' => esc_html__('Advanced', 'luckywp-cookie-notice-gdpr'),
        'sections' => [
            'advanced' => [
                'fields' => [

                    'cookieExpire' => [
                        'label' => esc_html__('Cookie Expire', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'textInput',
                        'params' => [
                            'inputOptions' => [
                                'size' => AdminHtml::TEXT_INPUT_SIZE_SMALL,
                            ],
                            'after' => ' ' . esc_html__('days', 'luckywp-cookie-notice-gdpr'),
                        ],
                        'default' => Plugin::DEFAULT_COOKIE_EXPIRE,
                    ],

                    'reloadAfterAccept' => [
                        'label' => esc_html__('Reload page…', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('after button "Accept" click', 'luckywp-cookie-notice-gdpr'),
                            ],
                        ],
                        'default' => false,
                    ],

                    'reloadAfterReject' => [
                        'label' => '',
                        'widget' => 'checkbox',
                        'params' => [
                            'checkboxOptions' => [
                                'label' => esc_html__('after button "Reject" click', 'luckywp-cookie-notice-gdpr'),
                            ],
                        ],
                        'default' => false,
                    ],

                    'cachingPluginsIntegration' => [
                        'label' => esc_html__('Caching plugins integration', 'luckywp-cookie-notice-gdpr'),
                        'widget' => 'select',
                        'params' => [
                            'items' => [
                                'auto' => esc_html__('Automatically', 'luckywp-cookie-notice-gdpr'),
                                'on' => esc_html__('Enable', 'luckywp-cookie-notice-gdpr'),
                                'off' => esc_html__('Disable', 'luckywp-cookie-notice-gdpr'),
                            ],
                        ],
                        'default' => 'auto',
                    ],
                ],
            ],
        ],
    ],

    'plugins' => [
        'label' => esc_html__('LuckyWP Plugins', 'luckywp-cookie-notice-gdpr'),
        'callback' => [SettingsController::getInstance(), 'plugins'],
    ],
];
