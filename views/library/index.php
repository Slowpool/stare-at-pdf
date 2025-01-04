<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var array[] $pdfFiles */

?>

<div id="library-container">
    <h4 id="library-title">Welcome. The library content:</h4>
    <nav id="library-toolbar">
        <ul id="library-toolbar-ul" class="navbar-nav nav">
            <li id="new-file-container" class="nav-item">
                <?= $this->render(Yii::getAlias('@partial_new_file_form'), compact('newFileModel')) ?>
            </li>
            <li id="new-category-container" class="nav-item">
                <?= $this->render(Yii::getAlias('@partial_new_category_form'), compact('newCategoryModel')) ?>
            </li>
            <li id="assign-category-container" class="nav-item">
                <?= $this->render(Yii::getAlias('@partial_assign_category_form'), compact('assignCategoryModel', 'pdfFileIds', 'categoryIds')) ?>
            </li>
        </ul>
    </nav>
    <ul id="all-files-list">
        <?php foreach ($pdfCards as $pdfCard): ?>
            <?= $this->render(Yii::getAlias('@partial_pdf_card'), compact('pdfCard')) ?>
        <?php endforeach; ?>
    </ul>
</div>