<?php

namespace app\models\jsonResponses;

class FailedToUploadFileResponse extends JsonResponse
{
    public $newForm;

    public function __construct($newForm, $url)
    {
        parent::__construct($url);

        $this->responseType = 'new file form';

        $this->newForm = $newForm;
    }
}
