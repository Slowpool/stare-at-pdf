<?php

use yii\bootstrap5\Html;

?>

<li class="nav-item">
    <?= Html::beginForm(['/logout'], 'post', ['class' => 'ajax-action']) ?>
    <?= Html::submitButton(
        'Logout from "' . Html::encode(Yii::$app->user->identity->username) . '"',
        ['class' => 'nav-link btn btn-link logout']
    ) ?>
    <?= Html::endForm() ?>
</li>