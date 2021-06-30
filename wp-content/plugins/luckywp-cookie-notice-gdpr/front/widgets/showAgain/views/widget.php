<?php
/**
 * @var $label string
 * @var $show bool
 * @var $colorScheme string
 * @var $position string
 * @var $marginBottom int
 * @var $marginSide int|null
 */

use luckywp\cookieNoticeGdpr\core\helpers\Html;

$classes = [
    'js-lwpcngShowAgain',
    'lwpcngShowAgain',
    'lwpcngShowAgain-' . ($marginBottom ? 'bottomFloat' : 'bottomSticked'),
    'lwpcngShowAgain-' . ($marginSide ? 'sideFloat' : 'sideSticked'),
    'lwpcngShowAgain-' . $position,
    'lwpcngShowAgain-' . $colorScheme,
    'lwpcngHide',
];
if (!$show) {
    $classes[] = 'lwpcngHide';
}

$style = [];
if ($marginBottom || $marginSide) {
    if ($marginBottom) {
        $style[] = 'bottom:' . $marginBottom . 'px';
    }
    if ($marginSide) {
        $style[] = ($position == 'bottomLeft' ? 'left' : 'right') . ':' . $marginSide . 'px';
    }
}
$style = $style ? implode(';', $style) : null;

echo Html::div($label, [
    'class' => $classes,
    'style' => $style,
]);
