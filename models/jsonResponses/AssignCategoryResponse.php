<?php

namespace app\models\jsonResponses;

use app\models\jsonResponses\BookmarkUpdateResponse;

class AssignCategoryResponse extends BookmarkUpdateResponse {
    public function __construct($assigningResult, $newForm) {
        // totally awkward
        parent::__construct($assigningResult, $newForm);
        
        $this->responseType = 'assign category result';
    }
}