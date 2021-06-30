<?php

namespace luckywp\cookieNoticeGdpr\core\helpers;

class Html
{

    /**
     * @var array
     * @see https://www.w3.org/TR/html/syntax.html#void-elements
     */
    public static $voidElements = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    ];

    /**
     * @param string|false|null $name
     * @param string $content
     * @param array $options
     * @return string
     */
    public static function tag($name, $content = '', $options = [])
    {
        if ($name === null || $name === false) {
            return $content;
        }
        $html = "<$name" . static::renderTagAttributes($options) . '>';
        return array_key_exists(strtolower($name), static::$voidElements) ? $html : "$html$content</$name>";
    }

    /**
     * @param string|false|null $name
     * @param array $options
     * @return string
     */
    public static function beginTag($name, $options = [])
    {
        if ($name === null || $name === false) {
            return '';
        }
        return "<$name" . static::renderTagAttributes($options) . '>';
    }

    public static function renderTagAttributes($attributes)
    {
        $html = '';
        foreach ($attributes as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= " $name";
                }
            } elseif (is_array($value)) {
                if ($name == 'data') {
                    foreach ($value as $n => $v) {
                        if (is_array($v)) {
                            $html .= " $name-$n='" . Json::htmlEncode($v) . "'";
                        } else {
                            $html .= " $name-$n=\"" . static::encode($v) . '"';
                        }
                    }
                } elseif ($name === 'class') {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . static::encode(implode(' ', $value)) . '"';
                } elseif ($name === 'style') {
                    if (empty($value)) {
                        continue;
                    }
                    $html .= " $name=\"" . static::encode(static::cssStyleFromArray($value)) . '"';
                } else {
                    $html .= " $name='" . Json::htmlEncode($value) . "'";
                }
            } elseif ($value !== null) {
                $html .= " $name=\"" . static::encode($value) . '"';
            }
        }

        return $html;
    }

    public static function encode($content, $doubleEncode = true)
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }

    public static function cssStyleFromArray(array $style)
    {
        $result = '';
        foreach ($style as $name => $value) {
            $result .= "$name: $value; ";
        }
        return $result === '' ? null : rtrim($result);
    }

    public static function prepareClassInOptions(array $options)
    {
        if (!isset($options['class'])) {
            $options['class'] = [];
        }
        if (!is_array($options['class'])) {
            $options['class'] = [$options['class']];
        }
        return $options;
    }

    public static function div($content, $options = [])
    {
        return static::tag('div', $content, $options);
    }

    public static function label($content, $for = null, $options = [])
    {
        $options['for'] = $for;
        return static::tag('label', $content, $options);
    }

    public static function input($type, $name = null, $value = null, $options = [])
    {
        if (!isset($options['type'])) {
            $options['type'] = $type;
        }
        $options['name'] = $name;
        $options['value'] = $value === null ? null : (string)$value;
        return static::tag('input', '', $options);
    }

    public static function textInput($name, $value = null, $options = [])
    {
        return static::input('text', $name, $value, $options);
    }

    /**
     * @param string $name
     * @param string $value
     * @param array $options
     * @return string
     */
    public static function textarea($name, $value = '', $options = [])
    {
        $options['name'] = $name;
        $doubleEncode = ArrayHelper::remove($options, 'doubleEncode', true);
        return static::tag('textarea', static::encode($value, $doubleEncode), $options);
    }

    /**
     * @param string $type "radio" или "checkbox"
     * @param string $name
     * @param bool $checked
     * @param array $options
     * @return string
     */
    protected static function booleanInput($type, $name, $checked = false, $options = [])
    {
        $options['checked'] = (bool)$checked;
        $value = array_key_exists('value', $options) ? $options['value'] : '1';

        $hidden = '';
        if (isset($options['uncheck'])) {
            $hiddenOptions = [];
            $hidden = static::hiddenInput($name, $options['uncheck'], $hiddenOptions);
            unset($options['uncheck']);
        }

        if (isset($options['label'])) {
            $label = $options['label'];
            $labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : [];
            unset($options['label'], $options['labelOptions']);
            $content = static::label(static::input($type, $name, $value, $options) . ' ' . $label, null, $labelOptions);
            return $hidden . $content;
        }
        return $hidden . static::input($type, $name, $value, $options);
    }

    /**
     * @param string $name
     * @param bool $checked
     * @param array $options
     * @return string
     */
    public static function checkbox($name, $checked = false, $options = [])
    {
        return static::booleanInput('checkbox', $name, $checked, $options);
    }

    /**
     * @param string $name
     * @param string $value
     * @param array $options
     * @return string
     */
    public static function hiddenInput($name, $value = null, $options = [])
    {
        return static::input('hidden', $name, $value, $options);
    }

    public static function a($text, $url = null, $options = [])
    {
        if ($url !== null) {
            $options['href'] = $url;
        }
        return static::tag('a', $text, $options);
    }

    /**
     * @param array|string|null $selection
     * @param $items
     * @param array $tagOptions
     * @return string
     */
    public static function renderSelectOptions($selection, $items, &$tagOptions = [])
    {
        if (is_array($selection)) {
            $selection = array_map('strval', (array)$selection);
        }

        $lines = [];
        $encodeSpaces = ArrayHelper::remove($tagOptions, 'encodeSpaces', false);
        $encode = ArrayHelper::remove($tagOptions, 'encode', true);
        if (isset($tagOptions['prompt'])) {
            $promptOptions = ['value' => ''];
            if (is_string($tagOptions['prompt'])) {
                $promptText = $tagOptions['prompt'];
            } else {
                $promptText = $tagOptions['prompt']['text'];
                $promptOptions = array_merge($promptOptions, $tagOptions['prompt']['options']);
            }
            $promptText = $encode ? static::encode($promptText) : $promptText;
            if ($encodeSpaces) {
                $promptText = str_replace(' ', '&nbsp;', $promptText);
            }
            $lines[] = static::tag('option', $promptText, $promptOptions);
        }

        $options = isset($tagOptions['options']) ? $tagOptions['options'] : [];
        $groups = isset($tagOptions['groups']) ? $tagOptions['groups'] : [];
        unset($tagOptions['prompt'], $tagOptions['options'], $tagOptions['groups']);
        $options['encodeSpaces'] = ArrayHelper::getValue($options, 'encodeSpaces', $encodeSpaces);
        $options['encode'] = ArrayHelper::getValue($options, 'encode', $encode);

        foreach ($items as $key => $value) {
            if (is_array($value)) {
                $groupAttrs = isset($groups[$key]) ? $groups[$key] : [];
                if (!isset($groupAttrs['label'])) {
                    $groupAttrs['label'] = $key;
                }
                $attrs = ['options' => $options, 'groups' => $groups, 'encodeSpaces' => $encodeSpaces, 'encode' => $encode];
                $content = static::renderSelectOptions($selection, $value, $attrs);
                $lines[] = static::tag('optgroup', "\n" . $content . "\n", $groupAttrs);
            } else {
                $attrs = isset($options[$key]) ? $options[$key] : [];
                $attrs['value'] = (string)$key;
                if (!array_key_exists('selected', $attrs)) {
                    $attrs['selected'] = $selection !== null &&
                        (!is_array($selection) && !strcmp($key, $selection)
                            || is_array($selection) && in_array((string)$key, $selection));
                }
                $text = $encode ? static::encode($value) : $value;
                if ($encodeSpaces) {
                    $text = str_replace(' ', '&nbsp;', $text);
                }
                $lines[] = static::tag('option', $text, $attrs);
            }
        }

        return implode("\n", $lines);
    }

    /**
     * @param string $name
     * @param string|array|null $selection
     * @param array $items
     * @param array $options
     * @return string
     */
    public static function dropDownList($name, $selection = null, $items = [], $options = [])
    {
        if (!empty($options['multiple'])) {
            return static::listBox($name, $selection, $items, $options);
        }
        $options['name'] = $name;
        unset($options['unselect']);
        $selectOptions = static::renderSelectOptions($selection, $items, $options);
        return static::tag('select', "\n" . $selectOptions . "\n", $options);
    }

    /**
     * @param string $name
     * @param string|array|null $selection
     * @param array $items
     * @param array $options
     * @return string
     */
    public static function listBox($name, $selection = null, $items = [], $options = [])
    {
        if (!array_key_exists('size', $options)) {
            $options['size'] = 4;
        }
        if (!empty($options['multiple']) && !empty($name) && substr_compare($name, '[]', -2, 2)) {
            $name .= '[]';
        }
        $options['name'] = $name;
        if (isset($options['unselect'])) {
            if (!empty($name) && substr_compare($name, '[]', -2, 2) === 0) {
                $name = substr($name, 0, -2);
            }
            $hidden = static::hiddenInput($name, $options['unselect']);
            unset($options['unselect']);
        } else {
            $hidden = '';
        }
        $selectOptions = static::renderSelectOptions($selection, $items, $options);
        return $hidden . static::tag('select', "\n" . $selectOptions . "\n", $options);
    }
}
