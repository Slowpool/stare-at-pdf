<?php

use app\models\library\AssignCategoryModel;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @param AssignCategoryModel $assignCategoryModel */

// dropDownList() requires this
$pdfFileIds = MapIdsToNames($pdfFileIds);
$categoryIds = MapIdsToNames($categoryIds);

function MapIdsToNames($array): array
{
    return sizeof($array) == 0
        ? []
        : array_reduce($array, function ($accum, $item) {
            $accum[$item['id']] = $item['name'];
            return $accum;
        });
}

?>

<h4>Assign <strong>category</strong></h4>
<?php $form = ActiveForm::begin(['action' => '/assign-category', 'method' => 'post', 'options' => ['id' => 'assign-category-form', 'class' => 'ajax-action']]) ?>
<?= $form->field($assignCategoryModel, 'pdfFileId')->dropDownList($pdfFileIds)->label('PDF file') ?>
<?= $form->field($assignCategoryModel, 'categoryId')->dropDownList($categoryIds)->label('Category') ?>
<?= Html::submitButton('Assign') ?>
<?php ActiveForm::end() ?>