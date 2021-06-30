<?php

namespace luckywp\cookieNoticeGdpr\core\admin\helpers;

use luckywp\cookieNoticeGdpr\core\helpers\ArrayHelper;
use luckywp\cookieNoticeGdpr\core\helpers\Html;

class AdminHtml
{

    const BUTTON_SIZE_SMALL = 'small';
    const BUTTON_SIZE_LARGE = 'large';
    const BUTTON_SIZE_HERO = 'hero';

    const BUTTON_THEME_PRIMARY = 'primary';
    const BUTTON_THEME_SECONDARY = 'secondary';
    const BUTTON_THEME_LINK = 'link';
    const BUTTON_THEME_LINK_DELETE = 'link button-link-delete';

    /**
     * @param $content
     * @param array $options
     * @return string
     */
    public static function button($content, $options = [])
    {
        $submit = isset($options['submit']) ? (bool)$options['submit'] : false;
        $href = isset($options['href']) ? $options['href'] : false;
        $class = isset($options['class']) ? $options['class'] : false;
        $size = isset($options['size']) ? $options['size'] : false;
        $theme = isset($options['theme']) ? $options['theme'] : false;
        $attrs = isset($options['attrs']) ? $options['attrs'] : [];
        $tag = isset($options['tag']) ? strtolower($options['tag']) : ($href ? 'a' : 'button');

        if (isset($attrs['class'])) {
            if (!is_array($attrs['class'])) {
                $attrs['class'] = explode(' ', $attrs['class']);
            }
        } else {
            $attrs['class'] = is_array($class) ? $class : ($class === false ? [] : [$class]);
        }
        $attrs['class'][] = 'button';

        if (($tag == 'input') && (!isset($attrs['value']))) {
            $attrs['value'] = $content;
        }

        if ($size) {
            $attrs['class'][] = 'button-' . $size;
        }

        if ($theme) {
            $attrs['class'][] = 'button-' . $theme;
        }

        if (($tag == 'a') && ($href !== false)) {
            $attrs['href'] = $href;
        }

        if (($tag == 'input') || ($tag == 'button')) {
            $attrs['type'] = $submit ? 'submit' : 'button';
        }

        return Html::tag($tag, $content, $attrs);
    }

    public static function buttonLink($content, $url, $options = [])
    {
        $options['tag'] = 'a';
        $options['href'] = $url;
        return static::button($content, $options);
    }

    const TEXT_INPUT_SIZE_REGULAR = 'regular';
    const TEXT_INPUT_SIZE_SMALL = 'small';
    const TEXT_INPUT_SIZE_LARGE = 'large';

    public static function textInput($name, $value, $options = [])
    {
        $options = Html::prepareClassInOptions($options);

        if ('' != $size = ArrayHelper::getValue($options, 'size')) {
            $options['class'][] = $size . '-text';
            unset($options['size']);
        } else {
            $options['class'][] = $size . 'regular-text';
        }

        return Html::textInput($name, $value, $options);
    }

    public static function textarea($name, $value, $options)
    {
        $options = Html::prepareClassInOptions($options);

        if ('' != $size = ArrayHelper::getValue($options, 'size')) {
            $options['class'][] = $size . '-text';
            unset($options['size']);
        } else {
            $options['class'][] = $size . 'regular-text';
        }

        $options['rows'] = ArrayHelper::getValue($options, 'rows', 6);
        $options['cols'] = ArrayHelper::getValue($options, 'cols', 60);

        return Html::textarea($name, $value, $options);
    }
}
