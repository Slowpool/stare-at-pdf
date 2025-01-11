<?php

namespace app\models\jsonResponses;

use app\models\jsonResponses\PageResponse;

class PageResponseWithIdentityAction extends PageResponse
{
    public $navbarItem;

    public function rules()
    {
        return [
            ...parent::rules(),
            [['navbarItem'], 'required']
        ];
    }

    public function __construct($pageResponse, $navbarItem)
    {
        parent::__construct($pageResponse->title, $pageResponse->content, $pageResponse->url);
        // yes. this thing absolutely must be implemented via flags like 0010101010. too much overhead
        $this->responseType = 'entire page with new identity action';

        $this->navbarItem = $navbarItem;
    }
}
