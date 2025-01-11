<?php

/** @var yii\web\View $this */
/** @var app\models\ErrorModel $errorModel */

use yii\helpers\Html;

?>
<div class="site-error">
    <h1><?= Html::encode($errorModel->name) ?></h1>

    <?php if ($errorModel->message): ?>
        <div class="alert alert-danger">
            <?= nl2br(Html::encode($errorModel->message)) ?>
        </div>
    <?php endif ?>

    <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>

</div>