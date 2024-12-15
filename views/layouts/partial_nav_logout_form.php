<?php

use yii\bootstrap5\Html;

?>

<li class="nav-item">
    <?= Html::beginForm(['/logout']) ?>
    <?= Html::submitButton(
        'Logout as a "' . Html::encode(Yii::$app->user->identity->username) . '"',
        ['class' => 'nav-link btn btn-link logout']
    ) ?>
    <?= Html::endForm() ?>
</li>