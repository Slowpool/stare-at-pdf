<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var array[] $pdfFiles */

use app\views\library\PdfFileCardGenerator;

?>

<div id="library-container">
    <h4 id="library-title">Welcome. The library content:</h4>
    <div id="new-file-container">
        <?= $this->render(Yii::getAlias('@partial_new_file_form'), compact('newFileModel')) ?>
    </div>
    <ul id="all-files-list">
        <?php foreach ($pdfFiles as $pdf): ?>
            <?= PdfFileCardGenerator::render($pdf['id'], $pdf['name'], $pdf['bookmark']) ?>
        <?php endforeach; ?>
    </ul>
</div>