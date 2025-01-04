<?php 

namespace app\models\jsonResponses;


class AddNewCategoryResponse extends BookmarkUpdateResponse {
    public function __construct($updateResult, $newForm) {
        // probably awkward
        parent::__construct($updateResult, $newForm);
        
        $this->responseType = 'new category add result';
    }
}