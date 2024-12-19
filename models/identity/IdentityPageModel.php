<?php

namespace app\models\identity;

use app\models\json_responses\PageResponse;

class IdentityPageResponse extends PageResponse
{
    public $navbarItem;

    public function rules()
    {
        return [
            ...parent::rules(),
            [['navbarItem'], 'required']
        ];
    }

    public function __construct($PageResponse, $navbarItem)
    {
        // yes. this thing absolutely must be implemented via flags like 0010101010. too much overhead
        $this->responseType = 'entire page with new identity action';
        
        // i'm sure it could be simpler
        $this->selectedNav = $PageResponse->selectedNav;
        $this->content = $PageResponse->content;
        $this->url = $PageResponse->url;
        
        $this->navbarItem = $navbarItem;
    }
}
