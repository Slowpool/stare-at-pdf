<?php 

namespace app\models\jsonResponses;


// bug: composer ignores this class
// solution: it doesn't ignore. i made the wrong order of files autoloading (turned out it matters). this class must be before his; derived class.

abstract class JsonRedirectResponse extends JsonResponse {
    public string $url;

    public function __construct($url) {
        $this->url = $url;
    }
}