<?php

namespace app\views\library;

use Yii;
use app\models\library\PdfCardModel;
use Exception;
use Throwable;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;

/** @param PdfCardModel $pdfCard */

$class = 'pdf-file-card';
if ($pdfCard['isAbandoned']) {
    $class .= ' abandoned';
}

// TODO despite to being implemented via array, view displayes a single color. (i don't know yet what to do with several colors. divide card on several parts with different colors? weird?)
$style = !empty($pdfCard['backgroundColors'])
    // when there's at least one color, the first one is obtained
    ? ['style' => "background-color: #" . $pdfCard['backgroundColors'][0]]
    : [];
?>

<?= Html::beginTag('li', array_merge(['class' => $class], $style)); ?>
<?php $url = Url::toRoute(['/stare-at/' . Html::encode(urlEncode($pdfCard->slug)), 'page' => Html::encode($pdfCard->bookmark)]) ?>
<?= Html::a(Html::encode($pdfCard->name), $url, ['class' => 'pdf-file-link ajax-action']); ?>
<span class="pdf-card-page">
    <?= " " . Html::encode($pdfCard->bookmark) . " p."; ?>
</span>

<?php $form = ActiveForm::begin(['action' => '/abandon-pdf-file', 'options' => ['id' => 'abandon-pdf-file-form', 'class' => 'ajax-action']]) ?>
<input id="pdfFileId" name="pdfFileId" type="hidden" value="<?= $pdfCard->id ?>">

<?= Html::submitButton('Abandon') ?>
<?php ActiveForm::end() ?>

<?= Html::endTag('li'); ?>