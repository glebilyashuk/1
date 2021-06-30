<?php

use luckywp\cookieNoticeGdpr\admin\Rate;
use luckywp\cookieNoticeGdpr\core\admin\helpers\AdminHtml;
use luckywp\cookieNoticeGdpr\core\Core;

?>
<div class="notice notice-info lwpcngRate">
    <p>
        <?= esc_html__('Hello!', 'luckywp-cookie-notice-gdpr') ?>
        <br>
        <?= sprintf(
        /* translators: %s: LuckyWP Cookie Notice (GDPR) */
            esc_html__('We are very pleased that you by now have been using the %s plugin a few days.', 'luckywp-cookie-notice-gdpr'),
            '<b>' . Core::$plugin->getName() . '</b>'
        ) ?>
        <br>
        <?= esc_html__('Please rate plugin. It will help us a lot.', 'luckywp-cookie-notice-gdpr') ?>
    </p>
    <p>
        <?= AdminHtml::buttonLink(esc_html__('Rate the plugin', 'luckywp-cookie-notice-gdpr'), Rate::LINK, [
            'attrs' => [
                'data-action' => 'lwpcng_rate',
                'target' => '_blank',
            ],
            'theme' => AdminHtml::BUTTON_THEME_PRIMARY,
        ]) ?>
        <?= AdminHtml::button(esc_html__('Remind later', 'luckywp-cookie-notice-gdpr'), [
            'attrs' => [
                'data-action' => 'lwpcng_rate_show_later',
            ],
            'theme' => AdminHtml::BUTTON_THEME_LINK,
        ]) ?>
        <?= AdminHtml::button(esc_html__('Don\'t show again', 'luckywp-cookie-notice-gdpr'), [
            'attrs' => [
                'data-action' => 'lwpcng_rate_hide',
            ],
            'theme' => AdminHtml::BUTTON_THEME_LINK,
        ]) ?>
    </p>
    <p>
        <b><?= esc_html__('Thank you very much!', 'luckywp-cookie-notice-gdpr') ?></b>
    </p>
</div>