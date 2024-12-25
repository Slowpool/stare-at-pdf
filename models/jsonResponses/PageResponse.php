<?php

namespace app\models\jsonResponses;

class PageResponse extends JsonRedirectResponse {
    public string $selectedNav;
    public string $content;

    // TODO why did i add it if it is never being validated?
    public function rules() {
        return [
            [['selectedNav', 'content', 'url'], 'required']
        ];
    }

    public function __construct($selectedNav, $content, $url) {
        parent::__construct($url);
        // yes, i could have used constants here, but idk yet how to agree php constants with js constants. probably it doesn't matter at all. 
        $this->responseType = 'entire page';
        
        $this->selectedNav = $selectedNav;
        $this->content = $content;
    }
}

