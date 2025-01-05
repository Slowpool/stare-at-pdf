<?php 

namespace app\models\jsonResponses;

use app\models\library\AddedCategoryModel;

class AddNewCategoryResponse extends BookmarkUpdateResponse {
    public ?AddedCategoryModel $addedCategoryModel;
    public function __construct($addingResult, $newForm, $addedCategoryModel = null) {
        parent::__construct($addingResult, $newForm);

        $this->responseType = 'new category add result';

        $this->addedCategoryModel = $addedCategoryModel;
    }
}