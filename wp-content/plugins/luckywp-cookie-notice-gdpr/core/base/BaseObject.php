<?php

namespace luckywp\cookieNoticeGdpr\core\base;

use Exception;
use luckywp\cookieNoticeGdpr\core\Core;

abstract class BaseObject
{

    public function __construct($config = [])
    {
        if (!empty($config)) {
            Core::configure($this, $config);
        }
        $this->init();
    }

    public function init()
    {
    }

    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        throw new Exception('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
            return;
        }
        throw new Exception('Setting unknown property: ' . get_class($this) . '::' . $name);
    }

    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }
        return false;
    }

    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
            return;
        }
        throw new Exception('Unsetting an unknown or read-only property: ' . get_class($this) . '::' . $name);
    }
}
