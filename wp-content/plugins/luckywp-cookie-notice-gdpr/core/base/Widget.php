<?php

namespace luckywp\cookieNoticeGdpr\core\base;

use luckywp\cookieNoticeGdpr\core\Core;
use ReflectionClass;

abstract class Widget extends BaseObject implements ViewContextInterface
{

    private $_viewPath;

    public function getViewPath()
    {
        if ($this->_viewPath === null) {
            $class = new ReflectionClass($this);
            $this->_viewPath = dirname($class->getFileName()) . '/views';
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
    public function render($view, $params = [], $echo = false)
    {
        $html = Core::$plugin->view->renderFile($this->getViewFiles($view), $params, $this);
        if ($echo) {
            echo $html;
            return null;
        }
        return $html;
    }

    /**
     * @param array $config
     * @return string
     */
    public static function widget($config = [])
    {
        /* @var $widget Widget */
        $config['class'] = get_called_class();
        $widget = Core::createObject($config);
        return $widget->run();
    }

    abstract public function run();
}
