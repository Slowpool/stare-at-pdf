<?php

namespace app\views\library;

use Yii;
use app\models\library\PdfCardModel;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @param PdfCardModel $pdfCard */
?>

<?= Html::beginTag('li', ['class' => 'pdf-file-card']); ?>
<?php $url = Url::toRoute(['/stare-at/' . Html::encode(urlEncode($pdfCard->name)), 'page' => Html::encode($pdfCard->bookmark)]) ?>
<?= Html::a(Html::encode($pdfCard->name), $url, ['class' => 'pdf-file-link ajax-action']); ?>
<?= " " . Html::encode($pdfCard->bookmark) . " p."; ?>
<?= Html::endTag('li'); ?>