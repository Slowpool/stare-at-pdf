<?php

use app\models\library\LibraryModel;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var LibraryModel $libraryModel */
/** @var array $pdfFileIds */
/** @var array $categoryIds */
?>

<div id="library-container">
    <h4 id="library-title">Welcome. The library content:</h4>
    <nav id="library-toolbar">
        <ul id="library-toolbar-ul" class="navbar-nav nav">
            <li id="new-file-container" class="nav-item library-toolbar-item">
                <?= $this->render(Yii::getAlias('@partial_new_file_form'), ['newFileModel' => $libraryModel->newFileModel]) ?>
            </li>
            <li id="new-category-container" class="nav-item library-toolbar-item">
                <?= $this->render(Yii::getAlias('@partial_new_category_form'), ['newCategoryModel' => $libraryModel->newCategoryModel]) ?>
            </li>
            <li id="assign-category-container" class="nav-item library-toolbar-item">
                <?= $this->render(Yii::getAlias('@partial_assign_category_form'), ['assignCategoryModel' => $libraryModel->assignCategoryModel, ...compact('pdfFileIds', 'categoryIds')]) ?>
            </li>
        </ul>
    </nav>
    <ul id="all-files-list">
        <?php foreach ($libraryModel->pdfCards as $pdfCard): ?>
            <?= $this->render(Yii::getAlias('@partial_pdf_card'), compact('pdfCard')) ?>
        <?php endforeach; ?>
    </ul>
</div>