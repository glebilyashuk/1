<?php

namespace luckywp\cookieNoticeGdpr\plugin;

use luckywp\cookieNoticeGdpr\core\base\BaseObject;
use luckywp\cookieNoticeGdpr\core\Core;

class Activation extends BaseObject
{

    public function init()
    {
        register_activation_hook(Core::$plugin->fileName, [$this, 'activate']);
    }

    public function activate()
    {
        Core::$plugin->loadTextDomain();
        Core::$plugin->settings->install();
    }
}
