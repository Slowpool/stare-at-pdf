<?php

namespace app\models\jsonResponses;

class AbandonPdfFileResponse extends BookmarkUpdateResponse
{
    public bool $success;
    public function __construct($success)
    {
        $this->responseType = 'abandon pdf file result';

        $this->success = $success;
    }
}