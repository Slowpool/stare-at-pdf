<?php 

namespace app\models\jsonResponses;


// bug: composer ignores this class
// solution: it doesn't ignore. i made the wrong order of files autoloading (turned out it matters). this class must be before his; derived class.

// TODO awkward inheritance from the point of logic
abstract class JsonResponse extends UrllessJsonResponse {
    public string $url;

    public function __construct($url) {
        $this->url = $url;
    }
}