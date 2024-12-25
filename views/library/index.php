<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var array[] $pdfFiles */

?>

<div id="library-container">
    <h4 id="library-title">Welcome. The library content:</h4>
    <div id="new-file-container">
        <?= $this->render(Yii::getAlias('@partial_new_file_form'), compact('newFileModel')) ?>
    </div>
    <ul id="all-files-list">
        <?php foreach ($pdfCards as $pdfCard): ?>
            <?= $this->render(Yii::getAlias('@partial_pdf_card'), compact('pdfCard')) ?>
        <?php endforeach; ?>
    </ul>
</div>