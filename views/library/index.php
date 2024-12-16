<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var array[] $pdfFiles */


use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\views\library\PdfFileCardGenerator;

?>

<div id="library-container">
    <h4>Welcome. The library content:</h4>
    <ul id="all-files-list">
        <?php foreach ($pdfFiles as $pdf): ?>
            <?= PdfFileCardGenerator::generate($pdf['id'], $pdf['name'], $pdf['bookmark']) ?>
        <?php endforeach; ?>
    </ul>
</div>