<?php

namespace luckywp\cookieNoticeGdpr\core\base;

use luckywp\cookieNoticeGdpr\core\helpers\ArrayHelper;

class Request extends BaseObject
{

    /**
     * Возвращает значение, переданное методом GET.
     * Значение приводится к исходному виду (по-умолчанию, WP экранирует символы)
     * Если значения не существует, то функция вернёт $defaultValue
     * @param string|null $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get($name = null, $defaultValue = null)
    {
        $value = $name === null ? $_GET : ArrayHelper::getValue($_GET, $name, $defaultValue);
        return $value === null ? null : stripslashes_deep($value);
    }

    /**
     * Возвращает значение, переданное методом POST.
     * Значение приводится к исходному виду (по-умолчанию, WP экранирует символы)
     * Если значения не существует, то функция вернёт $defaultValue
     * @param string|null $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function post($name = null, $defaultValue = null)
    {
        $value = $name === null ? $_POST : ArrayHelper::getValue($_POST, $name, $defaultValue);
        return $value === null ? null : stripslashes_deep($value);
    }
}
