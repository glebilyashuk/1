<?php

namespace luckywp\cookieNoticeGdpr\core\base;

use Closure;
use Exception;
use luckywp\cookieNoticeGdpr\core\Core;

class ServiceLocator extends BaseObject
{

    /**
     * @var array
     */
    private $_components = [];

    /**
     * @var array component
     */
    private $_definitions = [];

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        if ($this->has($name)) {
            return $this->get($name);
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        if ($this->has($name)) {
            return true;
        }
        return parent::__isset($name);
    }

    /**
     * @param string $id
     * @param bool $checkInstance
     * @return bool
     * @see set()
     */
    public function has($id, $checkInstance = false)
    {
        return $checkInstance ? isset($this->_components[$id]) : isset($this->_definitions[$id]);
    }

    /**
     * @param string $id
     * @param bool $throwException
     * @return object|null
     * @throws Exception
     */
    public function get($id, $throwException = true)
    {
        if (isset($this->_components[$id])) {
            return $this->_components[$id];
        }

        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($definition) && !$definition instanceof Closure) {
                return $this->_components[$id] = $definition;
            }
            return $this->_components[$id] = Core::createObject($definition);
        }

        if ($throwException) {
            throw new Exception("Unknown component ID: $id");
        }
        return null;
    }

    /**
     * @param string $id
     * @param mixed $definition
     */
    public function set($id, $definition)
    {
        unset($this->_components[$id]);

        if ($definition === null) {
            unset($this->_definitions[$id]);
            return;
        }

        if (is_object($definition) || is_callable($definition, true)) {
            $this->_definitions[$id] = $definition;
        } elseif (is_array($definition)) {
            $this->_definitions[$id] = $definition;
        }
    }

    /**
     * @param array $components
     */
    public function setComponents($components)
    {
        foreach ($components as $id => $component) {
            $this->set($id, $component);
        }
    }
}
