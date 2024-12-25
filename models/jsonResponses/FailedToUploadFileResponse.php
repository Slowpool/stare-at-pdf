<?php

namespace app\models\jsonResponses;

class FailedToUploadFileResponse extends JsonResponse
{
    public $newForm;

    public function __construct($newForm)
    {
        $this->responseType = 'new file form';

        $this->newForm = $newForm;
    }
}
