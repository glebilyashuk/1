<?php

namespace luckywp\cookieNoticeGdpr\core\helpers;

class ArrayHelper
{

    /**
     * @param array|object $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($array, $key, $default = null)
    {
        if (is_object($array)) {
            return $array->$key;
        }
        if (is_array($array)) {
            return array_key_exists($key, $array) ? $array[$key] : $default;
        }
        return $default;
    }

    /**
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function remove(&$array, $key, $default = null)
    {
        if (is_array($array) && array_key_exists($key, $array)) {
            $value = $array[$key];
            unset($array[$key]);
            return $value;
        }
        return $default;
    }
}
