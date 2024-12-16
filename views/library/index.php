<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var array[] $pdfFiles */


use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div id="library-container">
    the library content:
    <?php foreach ($pdfFiles as $pdf): ?>
        <?= PdffileCardGenerator::generate($pdf['id'], $pdf['name'], $pdf['bookmark']) ?>
    <?php endforeach; ?>
</div>