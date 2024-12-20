<?php

namespace app\models\jsonResponses;

// TODO bad names
class FileSuccessfullyUploadedResponse extends FailedToUploadFileResponse
{
    public $newPdfCard;

    public function __construct($failedToUploadFileResponse, $newPdfCard)
    {
        parent::__construct($failedToUploadFileResponse->newForm, $failedToUploadFileResponse->url);

        $this->responseType = 'new file form with previous uploaded pdf card';

        $this->newPdfCard = $newPdfCard;
    }
}
