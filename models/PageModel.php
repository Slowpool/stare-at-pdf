<?php

namespace app\models;

use Yii;
use yii\base\Model;


class PageModel extends Model {
    
    public string $selected_nav;
    public string $content;
    public string $url;

    public function rules() {
        return [
            [['selected_nav', 'content', 'url'], 'required']
        ];
    }

    public function __construct($selected_nav, $content, $url) {
        $this->selected_nav = $selected_nav;
        $this->content = $content;
        $this->url = $url;
    }
}

