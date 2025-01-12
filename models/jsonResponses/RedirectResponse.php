<?php

namespace app\models\jsonResponses;

class RedirectResponse extends JsonResponse {
    public string $destinationUrl;
    public function __construct(string $destinationUrl) {
        $this->responseType = 'redirect';
        $this->destinationUrl = $destinationUrl;
    }
}