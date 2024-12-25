<?php

namespace app\models\jsonResponses;

use app\models\jsonResponses\JsonResponse;

class BookmarkUpdateResponse extends JsonResponse {
    public bool $updateResult;
    public function __construct($updateResult) {
        $this->responseType = 'bookmark update result';
        $this->updateResult = $updateResult;
    }
}