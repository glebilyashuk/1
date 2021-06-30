<?php

namespace luckywp\cookieNoticeGdpr\core\base;

class View extends BaseObject
{

    /**
     * @var array
     */
    private $_vars = [];

    /**
     * @var ViewContextInterface
     */
    public $context;

    /**
     * @param string $view
     * @param array $params
     * @param ViewContextInterface $context
     * @return bool|string
     */
    public function render($view, $params = [], $context = null)
    {
        $currentContext = $context ? $context : $this->context;
        return $this->renderFile($currentContext->getViewFiles($view), $params, $context);
    }

    /**
     * @param array|string $files
     * @param array $vars
     * @param ViewContextInterface|null $context
     * @return bool|string
     */
    public function renderFile($files, $vars, $context = null)
    {
        $count = count($this->_vars);
        $this->_vars[$count] = $count > 0 ? array_merge($this->_vars[$count - 1], $vars) : $vars;

        if (!is_array($files)) {
            $files = [$files];
        }
        $file = false;
        foreach ($files as $f) {
            if (file_exists($f)) {
                $file = $f;
                break;
            }
        }
        if ($file === false) {
            return false;
        }

        $oldContext = $this->context;
        if ($context !== null) {
            $this->context = $context;
        }
        $result = $this->renderPhpFile($file, $this->_vars[$count]);
        $this->context = $oldContext;

        unset($this->_vars[$count]);

        return $result;
    }

    /**
     * @param string $____f
     * @param array $____v
     *
     * @return false|string
     */
    public function renderPhpFile($____f, $____v)
    {
        extract($____v);
        if (!file_exists($____f)) {
            return false;
        }
        ob_start();
        require $____f;
        return ob_get_clean();
    }
}
