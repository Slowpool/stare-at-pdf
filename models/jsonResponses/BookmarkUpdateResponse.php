<?php

namespace app\models\jsonResponses;

use app\models\jsonResponses\UrllessJsonResponse;

class BookmarkUpdateResponse extends UrllessJsonResponse {
    public bool $updateResult;
    public function __construct($updateResult) {
        $this->responseType = 'bookmark update result';
        $this->updateResult = $updateResult;
    }
}