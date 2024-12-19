<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $this this view is partial because it is rendered the first time on the library loading, and the second time after a file uploading */
?>

<?php $form = ActiveForm::begin(['action' => '/upload-pdf', 'method' => 'post', 'options' => ['id' => 'upload-file-form', 'class' => 'ajax-action']]) ?>
<?= $form->field($newFileModel, 'newFile')->fileInput()->label('New <strong>PDF</strong> file') ?>
<?= Html::submitButton('Add it to my library') ?> 
<?php ActiveForm::end() ?>