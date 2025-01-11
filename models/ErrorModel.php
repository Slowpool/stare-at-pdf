<?php

namespace app\models;

class ErrorModel {
    public string $name;
    public string $message;
    public function __construct($name, $message) {
        $this->name = $name;
        $this->message = $message;
    }
}