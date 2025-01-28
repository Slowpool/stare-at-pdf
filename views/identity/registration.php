<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\jsonResponses\PageResponse $page */

/** @var app\models\identity\RegistrationForm $registrationForm */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\identity\RegistrationForm;

?>

<div id="registration-container">
    <h1>Registration</h1>
    <p>Please fill out the following fields to register:</p>
    <div class="row">
        <?php $form = ActiveForm::begin([
            'id' => 'registration-form',
            'action' => '/send-credentials-to-register',
            'options' => ['class' => 'ajax-action'],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>
        <?= $form->field($registrationForm, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($registrationForm, 'password')->passwordInput() ?>
        <?= $form->field($registrationForm, 'rememberMe')->checkbox([
            'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>
        <div>
            <?= Html::submitButton('Become the starer (Sign up)', ['class' => 'btn btn-primar', 'name' => 'retistration -button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>