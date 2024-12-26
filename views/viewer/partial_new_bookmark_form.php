<?php
use yii\bootstrap5\Html;

?>

<?= Html::beginForm('/update-bookmark', 'post', ['class' => 'ajax-action']) ?>
<?= Html::input('hidden', 'pdfName', Html::encode($pdfName)) ?>
<?= Html::input('number', 'newBookmark', null, ['id' => 'newBookmarkInput', 'min' => 0, 'placeholder' => 'New bookmark']) ?>
<?= Html::submitButton('Chirk') ?>
<?= Html::endForm() ?>