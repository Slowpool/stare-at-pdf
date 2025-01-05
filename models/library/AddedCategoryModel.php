<?php

namespace app\models\library;

class AddedCategoryModel {
    public string $name;
    public string $id;
    public function __construct($name, $id) {
        $this->name = $name;
        $this->id = $id;
    }
}