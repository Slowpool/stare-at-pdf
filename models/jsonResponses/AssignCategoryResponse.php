<?php

namespace app\models\jsonResponses;

use app\models\jsonResponses\BookmarkUpdateResponse;

// TODO i totally failed with class hierarchy
class AssignCategoryResponse extends BookmarkUpdateResponse {
    // TODO add returned color
    public function __construct($assigningResult, $newForm) {
        // totally awkward
        parent::__construct($assigningResult, $newForm);

        $this->responseType = 'category assigning result';
    }
}