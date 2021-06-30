<?php

namespace luckywp\cookieNoticeGdpr\core\base;

use luckywp\cookieNoticeGdpr\core\Core;
use ReflectionClass;

/**
 * @property string $id
 */
abstract class Controller extends BaseObject implements ViewContextInterface
{

    private $_id;

    public function getId()
    {
        if ($this->_id === null) {
            $class = new ReflectionClass($this);
            $this->_id .= lcfirst(substr($class->getShortName(), 0, -10));
        }
        return $this->_id;
    }

    private $_viewPath;

    public function getViewPath()
    {
        if ($this->_viewPath === null) {
            $class = new ReflectionClass($this);
            $this->_viewPath = dirname($class->getFileName()) . '/../views';
            $this->_viewPath .= '/' . $this->id;
        }
        return $this->_viewPath;
    }

    /**
     * @param string $view
     * @return array
     */
    public function getViewFiles($view)
    {
        return [$this->getViewPath() . '/' . $view . '.php'];
    }

    /**
     * @param string $view
     * @param array $params
     * @param bool $echo
     * @return string|null
     */
    public function render($view, $params = [], $echo = true)
    {
        $html = Core::$plugin->view->renderFile($this->getViewFiles($view), $params, $this);
        if ($echo) {
            echo $html;
            return null;
        }
        return $html;
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        static $instances = [];
        $className = static::class;
        if (!isset($instances[$className])) {
            $instances[$className] = Core::createObject($className);
        }
        return $instances[$className];
    }
}
