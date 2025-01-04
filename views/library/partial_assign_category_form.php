<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

// dropDownList() requires it
$pdfFileIds = MapIdsToNames($pdfFileIds);
$pdfFileIds = MapIdsToNames($pdfFileIds);

function MapIdsToNames($array): array
{
    return array_reduce($array, function ($accum, $item) {
        $accum[$item['id']] = $item['name'];
        return $accum;
    });
}

?>

<h4>Assign category</h4>
<?php $form = ActiveForm::begin(['action' => '/assign-category', 'method' => 'post', 'options' => ['id' => 'assign-category-form', 'class' => 'ajax-action']]) ?>
<?= $form->field($assignCategoryModel, 'pdfFileId')->dropDownList($pdfFileIds) ?>
<?= $form->field($assignCategoryModel, 'categoryId')->dropDownList($categoryIds) ?>
<?= Html::submitButton('Assign') ?>
<?php ActiveForm::end() ?>