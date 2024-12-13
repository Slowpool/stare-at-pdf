<?php

namespace app\models;

use Yii;
use yii\base\Model;


class PageModel extends Model {
    
    public string $selected_nav;
    public string $title;
    public string $content;

    public function rules() {
        return [
            [['selected_nav', 'title', 'content'], 'required']
        ];
    }

    public function __construct($selected_nav, $title, $content) {
        $this->selected_nav = $selected_nav;
        $this->title = $title;
        $this->content = $content;
    }
}

