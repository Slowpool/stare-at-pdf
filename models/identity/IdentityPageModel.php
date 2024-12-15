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

    public function __construct($pageModel, $navbarItem)
    {
        foreach($pageModel as $property => $value) {
            // waaat
            $this->$property = $value;
        }
        $this->navbarItem = $navbarItem;
    }
}
