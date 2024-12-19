<?php 

namespace app\models\jsonResponses;

class UploadFileResponse extends JsonResponse {
    public $newForm;

    public function __construct($newForm, $url) {
        $this->responseType = 'new file form';
        
        $this->newForm = $newForm;
        $this->url = $url;
    }
}