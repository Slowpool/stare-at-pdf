<?php

namespace app\models\jsonResponses;

use app\models\jsonResponses\JsonResponse;

class BookmarkUpdateResponse extends JsonResponse {
    public bool $updateResult;
    public string $newForm;
    public function __construct($updateResult, $newForm) {
        $this->responseType = 'bookmark update result';

        $this->updateResult = $updateResult;
        $this->newForm = $newForm;
    }
}