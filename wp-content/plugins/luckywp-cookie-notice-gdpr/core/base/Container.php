<?php

namespace luckywp\cookieNoticeGdpr\core\base;

use Exception;
use luckywp\cookieNoticeGdpr\core\Core;
use ReflectionClass;
use ReflectionException;

class Container
{
    private $_singletons = [];
    private $_definitions = [];
    private $_params = [];
    private $_reflections = [];

    /**
     * @param string $class
     * @param array $params
     * @param array $config
     * @return object
     * @throws ReflectionException
     */
    public function get($class, $params = [], $config = [])
    {
        if (isset($this->_singletons[$class])) {
            return $this->_singletons[$class];
        } elseif (!isset($this->_definitions[$class])) {
            return $this->build($class, $params, $config);
        }

        $definition = $this->_definitions[$class];

        if (is_callable($definition, true)) {
            $params = $this->mergeParams($class, $params);
            $object = call_user_func($definition, $this, $params, $config);
        } elseif (is_array($definition)) {
            $concrete = $definition['class'];
            unset($definition['class']);

            $config = array_merge($definition, $config);
            $params = $this->mergeParams($class, $params);

            if ($concrete === $class) {
                $object = $this->build($class, $params, $config);
            } else {
                $object = $this->get($concrete, $params, $config);
            }
        } elseif (is_object($definition)) {
            return $this->_singletons[$class] = $definition;
        } else {
            throw new Exception('Unexpected object definition type: ' . gettype($definition));
        }

        if (array_key_exists($class, $this->_singletons)) {
            $this->_singletons[$class] = $object;
        }

        return $object;
    }

    /**
     * @param string $class
     * @param string|array|callable $definition
     * @param array $params
     * @return $this
     * @throws Exception
     */
    public function set($class, $definition = [], array $params = [])
    {
        $this->_definitions[$class] = $this->normalizeDefinition($class, $definition);
        $this->_params[$class] = $params;
        unset($this->_singletons[$class]);
        return $this;
    }

    /**
     * @param string $class
     * @param string|array|callable $definition
     * @param array $params
     * @return $this
     * @throws Exception
     */
    public function setSingleton($class, $definition = [], array $params = [])
    {
        $this->_definitions[$class] = $this->normalizeDefinition($class, $definition);
        $this->_params[$class] = $params;
        $this->_singletons[$class] = null;
        return $this;
    }

    /**
     * @param string $class
     * @param array $params
     * @return array
     */
    protected function mergeParams($class, $params)
    {
        if (empty($this->_params[$class])) {
            return $params;
        } elseif (empty($params)) {
            return $this->_params[$class];
        }

        $ps = $this->_params[$class];
        foreach ($params as $index => $value) {
            $ps[$index] = $value;
        }

        return $ps;
    }

    /**
     * @param string $class
     * @param string|array|callable $definition
     * @return array
     * @throws Exception
     */
    protected function normalizeDefinition($class, $definition)
    {
        if (empty($definition)) {
            return ['class' => $class];
        } elseif (is_string($definition)) {
            return ['class' => $definition];
        } elseif (is_callable($definition, true) || is_object($definition)) {
            return $definition;
        } elseif (is_array($definition)) {
            if (!isset($definition['class'])) {
                if (strpos($class, '\\') !== false) {
                    $definition['class'] = $class;
                }
            }
            return $definition;
        }
        throw new Exception('Unsupported definition type for "' . $class . '": ' . gettype($definition));
    }

    /**
     * @param string $class
     * @param array $params
     * @param array $config
     * @return object
     * @throws ReflectionException
     */
    protected function build($class, array $params, array $config)
    {
        if (isset($this->_reflections[$class])) {
            $reflection = $this->_reflections[$class];
        } else {
            $reflection = new ReflectionClass($class);
            $this->_reflections[$class] = $reflection;
        }

        if ($reflection->isSubclassOf(BaseObject::class)) {
            $params[] = $config;
            $object = $reflection->newInstanceArgs($params);
        } else {
            $object = $reflection->newInstanceArgs($params);
            if ($config) {
                Core::configure($object, $config);
            }
        }

        return $object;
    }
}
