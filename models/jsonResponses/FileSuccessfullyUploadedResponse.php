<?php

namespace app\models\jsonResponses;

use app\models\library\AddedPdfModel;
use app\models\library\PdfCardModel;

// TODO merge these classes in one. they indeed annoy
class FileSuccessfullyUploadedResponse extends FailedToUploadFileResponse
{
    public string $newPdfCard;
    public AddedPdfModel $addedPdfModel;

    public function __construct(FailedToUploadFileResponse $failedToUploadFileResponse, string $newPdfCard, AddedPdfModel $addedPdfModel)
    {
        parent::__construct($failedToUploadFileResponse->newForm);

        $this->responseType = 'new file form with previous uploaded pdf card';

        $this->newPdfCard = $newPdfCard;
        $this->addedPdfModel = $addedPdfModel;
    }
}
