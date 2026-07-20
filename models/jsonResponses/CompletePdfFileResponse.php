<?php

namespace app\models\jsonResponses;

class CompletePdfFileResponse extends JsonResponse
{
    public bool $success;
    public function __construct($success)
    {
        $this->responseType = 'complete pdf file result';

        $this->success = $success;
    }
}