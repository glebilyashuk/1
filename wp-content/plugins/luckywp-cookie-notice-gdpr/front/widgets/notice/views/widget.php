<?php
/**
 * @var $message string
 * @var $buttonAcceptLabel string
 * @var $buttonRejectLabel string
 * @var $moreLink string|null
 * @var $moreLabel string|null
 * @var $moreLinkTarget string|null
 * @var $show bool
 * @var $dataAttrs array
 * @var $colorScheme string
 * @var $template string
 * @var $position string
 * @var $margin int
 */

use luckywp\cookieNoticeGdpr\core\helpers\Html;

$classes = [
    'js-lwpcngNotice',
    'lwpcngNotice',
    'lwpcngNotice-' . ($margin ? 'float' : 'sticked'),
    'lwpcngNotice' . ucfirst($template),
    'lwpcngNotice' . ucfirst($template) . '-' . $position,
    'lwpcngNotice-' . $colorScheme,
    'lwpcngHide',
];
if (!$show) {
    $classes[] = 'lwpcngHide';
}

$style = null;
if ($margin) {
    if ($template == 'bar') {
        $style = 'left:' . $margin . 'px;right:' . $margin . 'px;';
        if ($position == 'top') {
            $style .= 'top:' . $margin . 'px';
        }
        if ($position == 'bottom') {
            $style .= 'bottom:' . $margin . 'px';
        }
    }
    if ($template == 'box') {
        if ($position == 'bottomLeft') {
            $style .= 'bottom:' . $margin . 'px;left:' . $margin . 'px;';
        }
        if ($position == 'bottomRight') {
            $style .= 'bottom:' . $margin . 'px;right:' . $margin . 'px;';
        }
        if ($position == 'topLeft') {
            $style .= 'top:' . $margin . 'px;left:' . $margin . 'px;';
        }
        if ($position == 'topRight') {
            $style .= 'top:' . $margin . 'px;right:' . $margin . 'px;';
        }
    }
}

echo Html::beginTag('div', [
    'class' => $classes,
    'data' => $dataAttrs,
    'style' => $style,
]);
?>
<div class="lwpcngNotice_message">
    <?= nl2br($message) ?>
</div>
<div class="lwpcngNotice_buttons">
    <div class="lwpcngNotice_accept js-lwpcngAccept">
        <?= $buttonAcceptLabel ?>
    </div>
    <?php if ($buttonRejectLabel) { ?>
        <div class="lwpcngNotice_reject js-lwpcngReject">
            <?= $buttonRejectLabel ?>
        </div>
    <?php } ?>
    <?php if ($moreLink) { ?>
        <div class="lwpcngNotice_more">
            <?= Html::a($moreLabel, $moreLink, $moreLinkTarget ? ['target' => $moreLinkTarget] : []) ?>
        </div>
    <?php } ?>
</div>
<?= '</div>' ?>
