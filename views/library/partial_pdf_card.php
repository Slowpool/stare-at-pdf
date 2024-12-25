<?php

namespace app\views\library;

use Yii;
use app\models\library\PdfCardModel;
use yii\bootstrap5\Html;

/** @param PdfCardModel $pdfCard */
?>

<?= Html::beginTag('li', ['class' => 'pdf-file-card']); ?>
<?= Html::a(Html::encode($pdfCard->name), '/stare-at/' . Html::encode(urlEncode($pdfCard->name)) . '?page=' . Html::encode($pdfCard->bookmark), ['class' => 'pdf-file-link ajax-action']); ?>
<?= " " . Html::encode($pdfCard->bookmark) . " p."; ?>
<?= Html::endTag('li'); ?>
