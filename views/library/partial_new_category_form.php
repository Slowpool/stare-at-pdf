<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<?php // TODO create AjaxActiveForm class which mix 'class' => 'ajax-action' in 'options' ?>
<?php $form = ActiveForm::begin(['action' => '/add-new-category', 'options' => ['id' => 'add-new-category-form', 'class' => 'ajax-action']]) ?>
<?= $form->field($newCategoryModel, 'name')->textInput(['maxlength' => Yii::getAlias('@MAX_CATEGORY_NAME_LENGTH')]) ?>
<?php //TODO remake it with color picker ?>
<?= $form->field($newCategoryModel, 'color')->textInput(['maxlength' => Yii::getAlias('@CATEGORY_COLOR_LENGTH')]) ?>
<?= Html::submitButton('Add') ?>
<?php ActiveForm::end()?>
