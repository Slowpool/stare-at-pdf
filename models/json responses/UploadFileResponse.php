<?php 

namespace app\models\json_responses;

abstract class UploadFileResponse extends JsonResponse {
    public string $responseType;
    public string $url;

}