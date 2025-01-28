<?php

use yii\bootstrap5\Html;

?>

<?= Html::a('Login', Yii::$app->user->loginUrl, ['class' => 'nav-link login-button ajax-action']) ?>

<?= Html::a('Registration', '/registration', ['class' => 'nav-link registration-button ajax-action']) ?>
