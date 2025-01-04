<?php 

namespace app\models\jsonResponses;


class AddNewCategoryResponse extends BookmarkUpdateResponse {
    public function __construct($addingResult, $newForm) {
        // probably awkward
        parent::__construct($addingResult, $newForm);

        $this->responseType = 'new category add result';
    }
}