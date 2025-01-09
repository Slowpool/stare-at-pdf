<?php

namespace app\views\library;

use Yii;
use app\models\library\PdfCardModel;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @param PdfCardModel $pdfCard */
?>

<?php
$style = !empty($pdfCard['backgroundColors'])
    // when there's at least one color, the first one is obtained
    ? ['style' => "background-color: #" . $pdfCard['backgroundColors'][0]]
    // no colors = no styles (sounds like )
    : [];
?>

<?= Html::beginTag('li', array_merge(['class' => 'pdf-file-card'], $style)); ?>
<?php $url = Url::toRoute(['/stare-at/' . Html::encode(urlEncode($pdfCard->slug)), 'page' => Html::encode($pdfCard->bookmark)]) ?>
<?= Html::a(Html::encode($pdfCard->name), $url, ['class' => 'pdf-file-link ajax-action']); ?>
<span class="pdf-card-page">
    <?= " " . Html::encode($pdfCard->bookmark) . " p."; ?>
</span>
<?= Html::endTag('li'); ?>