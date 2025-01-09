<?php

use yii\bootstrap5\Html;


?>

<?= Html::beginForm('/update-bookmark', 'post', ['class' => 'ajax-action']) ?>
<?= Html::input('hidden', 'pdfId', Html::encode($pdfId)) ?>
<?= Html::input('number', 'newBookmark', null, ['id' => 'newBookmarkInput', 'min' => 1, 'max' => PHP_INT_MAX, 'placeholder' => 'New bookmark']) ?>
<?= Html::submitButton('Chirk') ?>
<?= Html::endForm() ?>