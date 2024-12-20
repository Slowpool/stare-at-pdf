<?php

namespace app\models\jsonResponses;

// TODO inheritance names look strange
class FileSuccessfullyUploadedResponse extends FailedToUploadFileResponse
{
    public $newPdfCard;

    public function __construct($newForm, $url, $newPdfCard)
    {
        parent::__construct($newForm, $url);

        $this->responseType = 'new file form with previous uploaded pdf card';

        $this->newPdfCard = $newPdfCard;
    }
}