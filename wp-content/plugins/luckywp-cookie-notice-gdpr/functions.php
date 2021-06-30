<?php

use luckywp\cookieNoticeGdpr\core\Core;

/**
 * @return bool
 */
function lwpcng_cookies_accepted()
{
    return Core::$plugin->cookieAccepted;
}

/**
 * @return bool
 */
function lwpcng_cookies_rejected()
{
    return Core::$plugin->cookieRejected;
}
