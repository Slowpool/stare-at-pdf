<?php

namespace app\models\jsonResponses;

class PageResponse extends JsonRedirectResponse {
    /**
     * Or title, or current page name.
     * @var string
     */
    public string $selectedNav;
    /**
     * Will be placed into <body> into <main>.
     * @var string
     */
    public string $content;

    // TODO i would add it, but the architecture is so awkward that it'll take eternity
    // public string $descriptionMeta;

    // public string $keywordsMeta;

    // public array $linksMeta;
    

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

