<?php

use luckywp\cookieNoticeGdpr\admin\Rate;
use luckywp\cookieNoticeGdpr\core\Core;

?>
<div class="wrap">
    <a href="<?= Rate::LINK ?>" target="_blank" class="lwpcngSettingsRate"><?= sprintf(
        /* translators: %s: ★★★★★ */
            esc_html__('Leave a %s plugin review on WordPress.org', 'luckywp-cookie-notice-gdpr'),
            '★★★★★'
        ) ?></a>
    <h1><?= esc_html__('Cookie Notice (GDPR) Settings', 'luckywp-cookie-notice-gdpr') ?></h1>
    <?php Core::$plugin->settings->showPage(false) ?>
</div>