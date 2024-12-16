<?php

namespace app\models\identity;

use app\models\PageModel;

class IdentityPageModel extends PageModel
{
    public $navbarItem;

    public function rules()
    {
        return [
            ...parent::rules(),
            [['navbarItem'], 'required']
        ];
    }

    // sure it could be simpler
    public function __construct($pageModel, $navbarItem)
    {
        $this->selectedNav = $pageModel->selectedNav;
        $this->content = $pageModel->content;
        $this->url = $pageModel->url;
        $this->navbarItem = $navbarItem;
    }
}
