<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\PageModel $page */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\LoginForm;

// TODO view creates the model by its own???
$model = new LoginForm();

?>

<div class="site-login">
    <h1>Login</h1>
    <p>Please fill out the following fields to login:</p>
    <div class="row">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'action' => '/send-credentials-to-login',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'value' => 'admin']) ?>

            <?= $form->field($model, 'password')->passwordInput(['value' => 'admin']) ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div>
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <div style="color:#999;">
                You may login with <strong>john/john</strong>
            </div>
        </div>
</div>