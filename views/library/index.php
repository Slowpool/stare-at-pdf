<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\PageModel $page */


use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\LoginForm;

// TODO view creates the model by its own???

?>

<div id="library-container">
    the library content:
    <?php foreach ($model as $some): ?>
        <?= "$some<br>" ?>
    <?php endforeach; ?>
</div>