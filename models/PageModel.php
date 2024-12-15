<?php

namespace app\models;

use Yii;
use yii\base\Model;


class PageModel extends Model {
    
    public string $selectedNav;
    public string $content;
    public string $url;

    public function rules() {
        return [
            [['selectedNav', 'content', 'url'], 'required']
        ];
    }

    public function __construct($selectedNav, $content, $url) {
        $this->selectedNav = $selectedNav;
        $this->content = $content;
        $this->url = $url;
    }
}

