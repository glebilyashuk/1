<?php

namespace luckywp\cookieNoticeGdpr\core\front;

use luckywp\cookieNoticeGdpr\core\base\BaseObject;
use luckywp\cookieNoticeGdpr\core\base\ViewContextInterface;
use luckywp\cookieNoticeGdpr\core\Core;
use ReflectionClass;

/**
 * @property string $themeViewsDir
 */
class BaseFront extends BaseObject implements ViewContextInterface
{

    private $_theme_views_dir;

    protected $defaultThemeViewsDir;

    public function getThemeViewsDir()
    {
        if ($this->_theme_views_dir === null) {
            $this->_theme_views_dir = apply_filters(Core::$plugin->prefix . 'theme_views_dir', $this->defaultThemeViewsDir);
        }
        return $this->_theme_views_dir;
    }

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
        return [
            get_template_directory() . '/' . $this->themeViewsDir . '/' . $view . '.php',
            $this->getViewPath() . '/' . $view . '.php',
        ];
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
}
