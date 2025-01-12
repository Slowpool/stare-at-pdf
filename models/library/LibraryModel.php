<?php

namespace app\models\library;

class LibraryModel {
    public array $pdfCards;
    public NewFileModel $newFileModel;
    public NewCategoryModel $newCategoryModel;
    public AssignCategoryModel $assignCategoryModel;
    public function __construct(array $pdfCards, NewFileModel $newFileModel, NewCategoryModel $newCategoryModel, AssignCategoryModel $assignCategoryModel) {
        $this->pdfCards = $pdfCards;
        $this->newFileModel = $newFileModel;
        $this->newCategoryModel = $newCategoryModel;
        $this->assignCategoryModel = $assignCategoryModel;
    }
}