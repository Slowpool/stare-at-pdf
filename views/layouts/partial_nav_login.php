<?php

use yii\bootstrap5\Html;

?>

<?= Html::a('Login', Yii::$app->user->loginUrl, ['class' => 'nav-link login-button']) ?>